        const ETAT_SORTIE_STORAGE_KEY = 'etatsSortie';
        const ETAT_SORTIE_COUNTER_KEY = 'etatSortieCounter';
        let etatsSortie = [];
        let editingEtatSortieId = null;

        function loadEtatsSortie() {
            try {
                const parsed = JSON.parse(localStorage.getItem(ETAT_SORTIE_STORAGE_KEY) || '[]');
                etatsSortie = Array.isArray(parsed) ? parsed : [];
                let counter = Math.max(
                    Number(localStorage.getItem(ETAT_SORTIE_COUNTER_KEY) || 0),
                    ...etatsSortie.map(sortie => {
                        const match = String(sortie.numero_sortie || '').match(/(\d+)$/);
                        return match ? Number(match[1]) : 0;
                    })
                );
                let migrated = false;
                etatsSortie.forEach(sortie => {
                    if (sortie.numero_sortie) return;
                    counter += 1;
                    sortie.numero_sortie = 'ES' + String(counter).padStart(4, '0');
                    migrated = true;
                });
                localStorage.setItem(ETAT_SORTIE_COUNTER_KEY, String(counter));
                if (migrated) localStorage.setItem(ETAT_SORTIE_STORAGE_KEY, JSON.stringify(etatsSortie));
            } catch (error) {
                console.error('Lecture états sortie:', error);
                etatsSortie = [];
            }
            return etatsSortie;
        }

        function saveEtatsSortie() {
            localStorage.setItem(ETAT_SORTIE_STORAGE_KEY, JSON.stringify(etatsSortie));
        }

        function nextEtatSortieNumber() {
            const next = Number(localStorage.getItem(ETAT_SORTIE_COUNTER_KEY) || 0) + 1;
            localStorage.setItem(ETAT_SORTIE_COUNTER_KEY, String(next));
            return 'ES' + String(next).padStart(4, '0');
        }

        function formatSortieNumber(value) {
            return Number(value || 0).toLocaleString('fr-FR', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 3
            });
        }

        function getSortieProductionEtats() {
            if (typeof loadEtatsProduction === 'function') loadEtatsProduction();
            return Array.isArray(etatsProduction) ? etatsProduction : [];
        }

        function getSortieLineKey(ref, designation) {
            return typeof productionArticleKey === 'function'
                ? productionArticleKey(ref, designation)
                : (String(ref || '').trim() || String(designation || '').trim()).toLowerCase();
        }

        function getSortieProductionEtat(numero) {
            return getSortieProductionEtats().find(etat => String(etat.numero || '') === String(numero || '')) || null;
        }

        function getSortieLineUnit(ligne) {
            if (ligne?.unite) return ligne.unite;
            if (typeof getProductionDepotArticles === 'function') {
                const article = getProductionDepotArticles().get(getSortieLineKey(ligne?.ref, ligne?.designation));
                if (article?.unite) return article.unite;
            }
            if (Array.isArray(produits)) {
                const product = produits.find(item =>
                    getSortieLineKey(item.ref, item.designation) === getSortieLineKey(ligne?.ref, ligne?.designation)
                );
                if (product?.unite) return product.unite;
            }
            return '';
        }

        function getSortieRemaining(numero, ligne) {
            const initial = Number(ligne?.quantite) || 0;
            const key = getSortieLineKey(ligne?.ref, ligne?.designation);
            const used = loadEtatsSortie().reduce((sum, sortie) => {
                if (editingEtatSortieId && String(sortie.id) === String(editingEtatSortieId)) return sum;
                if (String(sortie.numero_production || '') !== String(numero || '')) return sum;
                if (getSortieLineKey(sortie.ref, sortie.designation) !== key) return sum;
                return sum + (Number(sortie.quantite) || 0);
            }, 0);
            return Math.max(0, initial - used);
        }

        function getSortieAvailableLines(numero) {
            const etat = getSortieProductionEtat(numero);
            if (!etat) return [];
            return (etat.lignes || []).map(ligne => ({
                ...ligne,
                unite: getSortieLineUnit(ligne),
                disponible: getSortieRemaining(numero, ligne)
            }));
        }

        function fillSortieProductionNumbers() {
            const select = document.getElementById('es_numero_production');
            if (!select) return;
            const selected = select.value;
            select.innerHTML = '<option value="">N° Etat production</option>' +
                getSortieProductionEtats().map(etat =>
                    `<option value="${escHtml(etat.numero || '')}">${escHtml(etat.numero || '')}</option>`
                ).join('');
            select.value = selected;
        }

        function fillSortieArticleLists(numero, selectedRef = '', selectedDesignation = '') {
            const ref = document.getElementById('es_ref');
            const designation = document.getElementById('es_designation');
            const lines = getSortieAvailableLines(numero);
            if (ref) {
                ref.innerHTML = '<option value="">Réf</option>' + lines
                    .filter(ligne => ligne.ref && (ligne.disponible > 0 || ligne.ref === selectedRef))
                    .map(ligne => `<option value="${escHtml(ligne.ref)}">${escHtml(ligne.ref)}</option>`)
                    .join('');
                ref.value = selectedRef;
            }
            if (designation) {
                designation.innerHTML = '<option value="">Désignation</option>' + lines
                    .filter(ligne => ligne.designation && (ligne.disponible > 0 || ligne.designation === selectedDesignation))
                    .map(ligne => `<option value="${escHtml(ligne.designation)}">${escHtml(ligne.designation)}</option>`)
                    .join('');
                designation.value = selectedDesignation;
            }
        }

        function applySortieLine(ligne) {
            const ref = document.getElementById('es_ref');
            const designation = document.getElementById('es_designation');
            const unite = document.getElementById('es_unite');
            const quantite = document.getElementById('es_quantite');
            if (!ligne) {
                if (unite) unite.value = '';
                if (quantite) {
                    quantite.value = '';
                    quantite.removeAttribute('max');
                }
                return;
            }
            if (ref) ref.value = ligne.ref || '';
            if (designation) designation.value = ligne.designation || '';
            if (unite) unite.value = ligne.unite || '';
            if (quantite) {
                quantite.max = String(ligne.disponible);
                quantite.title = 'Disponible : ' + formatSortieNumber(ligne.disponible);
            }
        }

        function findSortieLine(ref, designation) {
            const numero = document.getElementById('es_numero_production')?.value || '';
            const normalizedRef = String(ref || '').trim().toLowerCase();
            const normalizedDesignation = String(designation || '').trim().toLowerCase();
            return getSortieAvailableLines(numero).find(ligne =>
                (normalizedRef && String(ligne.ref || '').trim().toLowerCase() === normalizedRef)
                || (normalizedDesignation && String(ligne.designation || '').trim().toLowerCase() === normalizedDesignation)
            ) || null;
        }

        function renderEtatsSortieTable() {
            const tbody = document.getElementById('etatsSortieTableBody');
            if (!tbody) return;
            loadEtatsSortie();
            if (!etatsSortie.length) {
                tbody.innerHTML = '<tr><td colspan="7" class="achats-commandes-empty">Aucun état de sortie</td></tr>';
                return;
            }
            tbody.innerHTML = etatsSortie.slice().reverse().map(sortie => `
                <tr>
                    <td>${escHtml(typeof formatDateFr === 'function' ? formatDateFr(sortie.date) : sortie.date)}</td>
                    <td><strong>${escHtml(sortie.numero_production || '')}</strong></td>
                    <td>${escHtml(sortie.ref || '')}</td>
                    <td>${escHtml(sortie.designation || '')}</td>
                    <td>${formatSortieNumber(sortie.quantite)}</td>
                    <td>${escHtml(sortie.unite || '')}</td>
                    <td class="col-actions no-print-sortie">
                        <span class="col-actions-wrap">
                            <button type="button" class="btn-icon-row btn-icon-view" data-sortie-action="view" data-sortie-id="${escHtml(sortie.id)}" title="Voir" aria-label="Voir">
                                <svg viewBox="0 0 24 24" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            <button type="button" class="btn-icon-row btn-icon-edit" data-sortie-action="edit" data-sortie-id="${escHtml(sortie.id)}" title="Modifier" aria-label="Modifier">
                                <svg viewBox="0 0 24 24" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                            </button>
                            <button type="button" class="btn-icon-row btn-icon-delete" data-sortie-action="delete" data-sortie-id="${escHtml(sortie.id)}" title="Supprimer" aria-label="Supprimer">
                                <svg viewBox="0 0 24 24" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </span>
                    </td>
                </tr>
            `).join('');
        }

        function showEtatSortieMode(mode) {
            document.getElementById('etatSortieConsultMode')?.classList.toggle('hidden', mode !== 'consult');
            document.getElementById('etatSortieSaisieMode')?.classList.toggle('hidden', mode !== 'saisie');
        }

        function setEtatSortieReadOnly(readOnly) {
            ['es_numero_production', 'es_ref', 'es_designation', 'es_quantite'].forEach(id => {
                const element = document.getElementById(id);
                if (element) element.disabled = readOnly;
            });
            document.getElementById('validerEtatSortieBtn')?.classList.toggle('hidden', readOnly);
        }

        function resetEtatSortieForm() {
            editingEtatSortieId = null;
            const date = document.getElementById('es_date');
            if (date) date.value = typeof todayEtatProduction === 'function'
                ? todayEtatProduction()
                : new Date().toISOString().slice(0, 10);
            ['es_numero_production', 'es_ref', 'es_designation', 'es_quantite', 'es_unite'].forEach(id => {
                const element = document.getElementById(id);
                if (element) element.value = '';
            });
            fillSortieProductionNumbers();
            fillSortieArticleLists('');
            setEtatSortieReadOnly(false);
        }

        function openEtatSortie(id, readOnly) {
            loadEtatsSortie();
            const sortie = etatsSortie.find(item => String(item.id) === String(id));
            if (!sortie) return;
            editingEtatSortieId = sortie.id;
            fillSortieProductionNumbers();
            document.getElementById('es_date').value = sortie.date || '';
            document.getElementById('es_numero_production').value = sortie.numero_production || '';
            fillSortieArticleLists(sortie.numero_production, sortie.ref, sortie.designation);
            document.getElementById('es_quantite').value = sortie.quantite || '';
            document.getElementById('es_unite').value = sortie.unite || '';
            applySortieLine(findSortieLine(sortie.ref, sortie.designation));
            setEtatSortieReadOnly(readOnly);
            showEtatSortieMode('saisie');
        }

        function initEtatSortieView() {
            loadEtatsSortie();
            renderEtatsSortieTable();
            showEtatSortieMode('consult');
        }

        document.getElementById('nouvelEtatSortieBtn')?.addEventListener('click', () => {
            resetEtatSortieForm();
            showEtatSortieMode('saisie');
        });

        document.getElementById('fermerEtatSortieConsultBtn')?.addEventListener('click', () => {
            showAppView('dashboard');
        });

        document.getElementById('fermerEtatSortieBtn')?.addEventListener('click', () => {
            editingEtatSortieId = null;
            renderEtatsSortieTable();
            showEtatSortieMode('consult');
        });

        document.getElementById('es_numero_production')?.addEventListener('change', event => {
            fillSortieArticleLists(event.target.value);
            applySortieLine(null);
        });

        document.getElementById('es_ref')?.addEventListener('change', event => {
            applySortieLine(findSortieLine(event.target.value, ''));
        });

        document.getElementById('es_designation')?.addEventListener('change', event => {
            applySortieLine(findSortieLine('', event.target.value));
        });

        document.getElementById('validerEtatSortieBtn')?.addEventListener('click', () => {
            const numero = document.getElementById('es_numero_production')?.value || '';
            const ref = document.getElementById('es_ref')?.value || '';
            const designation = document.getElementById('es_designation')?.value || '';
            const ligne = findSortieLine(ref, designation);
            const quantite = Number(document.getElementById('es_quantite')?.value || 0);
            if (!numero) {
                alert('Sélectionnez le N° Etat production.');
                return;
            }
            if (!ligne) {
                alert('Sélectionnez une référence ou une désignation.');
                return;
            }
            if (!(quantite > 0)) {
                alert('La quantité doit être supérieure à zéro.');
                return;
            }
            if (quantite > ligne.disponible) {
                alert('Quantité supérieure à la quantité disponible (' + formatSortieNumber(ligne.disponible) + ').');
                return;
            }
            loadEtatsSortie();
            const data = {
                id: editingEtatSortieId || ('es-' + Date.now()),
                numero_sortie: editingEtatSortieId
                    ? (etatsSortie.find(item => String(item.id) === String(editingEtatSortieId))?.numero_sortie || nextEtatSortieNumber())
                    : nextEtatSortieNumber(),
                date: document.getElementById('es_date')?.value || '',
                numero_production: numero,
                ref: ligne.ref || ref,
                designation: ligne.designation || designation,
                quantite,
                unite: ligne.unite || document.getElementById('es_unite')?.value || ''
            };
            const index = etatsSortie.findIndex(item => String(item.id) === String(editingEtatSortieId));
            if (index >= 0) etatsSortie[index] = data;
            else etatsSortie.push(data);
            saveEtatsSortie();
            editingEtatSortieId = data.id;
            renderEtatsSortieTable();
            if (typeof renderDepotFiniTable === 'function') renderDepotFiniTable();
            if (typeof renderDashboardStockFinis === 'function') renderDashboardStockFinis();
            alert('Etat de sortie enregistré.');
        });

        document.getElementById('imprimerEtatsSortieBtn')?.addEventListener('click', () => {
            if (!loadEtatsSortie().length) {
                alert('Aucun état de sortie à imprimer.');
                return;
            }
            document.body.classList.add('print-etat-sortie');
            const cleanup = () => {
                document.body.classList.remove('print-etat-sortie');
                window.removeEventListener('afterprint', cleanup);
            };
            window.addEventListener('afterprint', cleanup);
            window.print();
            setTimeout(cleanup, 1500);
        });

        document.getElementById('etatsSortieTableBody')?.addEventListener('click', event => {
            const button = event.target.closest('[data-sortie-action]');
            if (!button) return;
            const id = button.dataset.sortieId;
            const action = button.dataset.sortieAction;
            if (action === 'view') openEtatSortie(id, true);
            if (action === 'edit') openEtatSortie(id, false);
            if (action === 'delete') {
                if (!confirm('Supprimer cet état de sortie ?')) return;
                loadEtatsSortie();
                etatsSortie = etatsSortie.filter(item => String(item.id) !== String(id));
                saveEtatsSortie();
                renderEtatsSortieTable();
                if (typeof renderDepotFiniTable === 'function') renderDepotFiniTable();
                if (typeof renderDashboardStockFinis === 'function') renderDashboardStockFinis();
            }
        });
