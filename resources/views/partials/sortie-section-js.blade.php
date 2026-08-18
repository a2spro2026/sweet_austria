        const ETAT_SORTIE_STORAGE_KEY = 'etatsSortie';
        const ETAT_SORTIE_COUNTER_KEY = 'etatSortieCounter';
        let etatsSortie = [];
        let editingEtatSortieId = null;

        function loadEtatsSortie() {
            try {
                const parsed = JSON.parse(localStorage.getItem(ETAT_SORTIE_STORAGE_KEY) || '[]');
                etatsSortie = Array.isArray(parsed) ? parsed : [];
                const formatNumero = typeof formatEtatNumero === 'function' ? formatEtatNumero : (prefix, n) => prefix + '/' + String(n).padStart(4, '0');
                const parseNumero = typeof parseEtatNumero === 'function' ? parseEtatNumero : (value) => {
                    const match = String(value || '').match(/(\d+)\s*$/);
                    return match ? Number(match[1]) : 0;
                };
                const normalizeNumero = typeof normalizeEtatNumero === 'function'
                    ? normalizeEtatNumero
                    : (value, prefix) => {
                        const n = parseNumero(value);
                        return n > 0 ? formatNumero(prefix, n) : '';
                    };
                let counter = 0;
                let migrated = false;
                etatsSortie.forEach(sortie => {
                    const nextSortie = normalizeNumero(sortie.numero_sortie, 'ES');
                    if (nextSortie && sortie.numero_sortie !== nextSortie) {
                        sortie.numero_sortie = nextSortie;
                        migrated = true;
                    }
                    if (!sortie.numero_sortie) {
                        counter += 1;
                        sortie.numero_sortie = formatNumero('ES', counter);
                        migrated = true;
                    }
                    const nextProduction = normalizeNumero(sortie.numero_production, 'EP');
                    if (nextProduction && sortie.numero_production !== nextProduction) {
                        sortie.numero_production = nextProduction;
                        migrated = true;
                    }
                    const nextDepense = normalizeNumero(sortie.numero_depense, 'ED');
                    if (nextDepense && sortie.numero_depense !== nextDepense) {
                        sortie.numero_depense = nextDepense;
                        migrated = true;
                    }
                    counter = Math.max(counter, parseNumero(sortie.numero_sortie));
                });
                if (!etatsSortie.length) counter = 0;
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
            loadEtatsSortie();
            const next = Number(localStorage.getItem(ETAT_SORTIE_COUNTER_KEY) || 0) + 1;
            localStorage.setItem(ETAT_SORTIE_COUNTER_KEY, String(next));
            return typeof formatEtatNumero === 'function'
                ? formatEtatNumero('ES', next)
                : ('ES/' + String(next).padStart(4, '0'));
        }

        function formatSortieNumber(value) {
            return Number(value || 0).toLocaleString('fr-FR', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 3
            });
        }

        function formatSortieMoney(value) {
            if (value == null || value === '') return '—';
            return Number(value).toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function getSortieProductionEtats() {
            if (typeof loadEtatsProduction === 'function') loadEtatsProduction();
            return Array.isArray(etatsProduction) ? etatsProduction : [];
        }

        function getSortieDepenseEtats(numeroProduction = '') {
            if (typeof loadEtatsDepense === 'function') loadEtatsDepense();
            const list = Array.isArray(etatsDepense) ? etatsDepense : [];
            if (!numeroProduction) return list;
            return list.filter(etat =>
                String(etat.numero_production || '') === String(numeroProduction)
                || (etat.lignes || []).some(ligne => String(ligne.numero_production || '') === String(numeroProduction))
            );
        }

        function getSortieDepenseEtat(numero) {
            return getSortieDepenseEtats().find(etat => String(etat.numero || '') === String(numero || '')) || null;
        }

        function fillSortieProductionNumbers(selected = '') {
            const select = document.getElementById('es_numero_production');
            if (!select) return;
            select.innerHTML = '<option value="">N° E/P</option>' +
                getSortieProductionEtats().map(etat =>
                    `<option value="${escHtml(etat.numero || '')}">${escHtml(etat.numero || '')}</option>`
                ).join('');
            if (selected) select.value = selected;
        }

        function fillSortieDepenseNumbers(numeroProduction = '', selected = '') {
            const select = document.getElementById('es_numero_depense');
            if (!select) return;
            const list = getSortieDepenseEtats(numeroProduction);
            select.innerHTML = '<option value="">N° E/D</option>' +
                list.map(etat =>
                    `<option value="${escHtml(etat.numero || '')}">${escHtml(etat.numero || '')}</option>`
                ).join('');
            if (selected) select.value = selected;
        }

        function fillSortieLookups(selectedCategorie = '') {
            if (typeof populateLookupSelect === 'function' && typeof LOOKUP_LISTS !== 'undefined') {
                populateLookupSelect('es_categorie', LOOKUP_LISTS.categories || [], selectedCategorie);
                return;
            }
            const select = document.getElementById('es_categorie');
            if (!select) return;
            const items = (typeof LOOKUP_LISTS !== 'undefined' ? LOOKUP_LISTS.categories : []) || [];
            select.innerHTML = '<option value="">Catégorie</option>' +
                items.map(item => `<option value="${escHtml(item)}">${escHtml(item)}</option>`).join('');
            if (selectedCategorie) select.value = selectedCategorie;
        }

        function applySortieProductMatch(ref, designation) {
            const list = Array.isArray(produits) ? produits : [];
            const key = typeof productionArticleKey === 'function'
                ? productionArticleKey(ref, designation)
                : (String(ref || '').trim() || String(designation || '').trim()).toLowerCase();
            if (!key) return;
            const product = list.find(item =>
                (typeof productionArticleKey === 'function'
                    ? productionArticleKey(item.ref, item.designation)
                    : String(item.ref || item.designation || '').toLowerCase()) === key
            );
            if (!product) return;
            fillSortieLookups(product.categorie || '');
            const setVal = (id, value) => {
                const el = document.getElementById(id);
                if (el && value) el.value = value;
            };
            setVal('es_ref', product.ref);
            setVal('es_designation', product.designation);
            setVal('es_categorie', product.categorie);
            setVal('es_unite', product.unite);
            if (product.prix_vente != null && product.prix_vente !== '' && !document.getElementById('es_prix_vente')?.value) {
                document.getElementById('es_prix_vente').value = Number(product.prix_vente).toFixed(2);
            }
        }

        function renderEtatsSortieTable() {
            const tbody = document.getElementById('etatsSortieTableBody');
            if (!tbody) return;
            loadEtatsSortie();
            if (!etatsSortie.length) {
                tbody.innerHTML = '<tr><td colspan="11" class="achats-commandes-empty">Aucun état de sortie</td></tr>';
                return;
            }
            tbody.innerHTML = etatsSortie.slice().reverse().map(sortie => `
                <tr>
                    <td>${escHtml(typeof formatDateFr === 'function' ? formatDateFr(sortie.date) : sortie.date)}</td>
                    <td><strong>${escHtml(sortie.numero_sortie || '')}</strong></td>
                    <td>${escHtml(sortie.numero_production || '')}</td>
                    <td>${escHtml(sortie.numero_depense || '')}</td>
                    <td>${escHtml(sortie.ref || '')}</td>
                    <td>${escHtml(sortie.designation || '')}</td>
                    <td>${escHtml(sortie.categorie || '')}</td>
                    <td>${formatSortieNumber(sortie.quantite)}</td>
                    <td>${escHtml(sortie.unite || '')}</td>
                    <td>${formatSortieMoney(sortie.prix_vente)}</td>
                    <td class="col-actions no-print-sortie">
                        <span class="col-actions-wrap">
                            <button type="button" class="btn-icon-row btn-icon-view" data-sortie-action="view" data-sortie-id="${escHtml(sortie.id)}" title="Voir" aria-label="Voir">
                                <svg viewBox="0 0 24 24" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            <button type="button" class="btn-icon-row btn-icon-edit" data-sortie-action="edit" data-sortie-id="${escHtml(sortie.id)}" title="Modifier" aria-label="Modifier">
                                <svg viewBox="0 0 24 24" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                            </button>
                            <button type="button" class="btn-icon-row btn-icon-print" data-sortie-action="print" data-sortie-id="${escHtml(sortie.id)}" title="Imprimer" aria-label="Imprimer">
                                <svg viewBox="0 0 24 24" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
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
            ['es_numero_production', 'es_numero_depense', 'es_ref', 'es_designation', 'es_categorie', 'es_quantite', 'es_unite', 'es_prix_vente'].forEach(id => {
                const element = document.getElementById(id);
                if (!element) return;
                if (element.tagName === 'SELECT') element.disabled = readOnly;
                else element.readOnly = readOnly;
            });
            document.getElementById('validerEtatSortieBtn')?.classList.toggle('hidden', readOnly);
        }

        function resetEtatSortieForm() {
            editingEtatSortieId = null;
            const date = document.getElementById('es_date');
            if (date) date.value = typeof todayEtatProduction === 'function'
                ? todayEtatProduction()
                : new Date().toISOString().slice(0, 10);
            const numero = document.getElementById('es_numero');
            if (numero) numero.value = nextEtatSortieNumber();
            ['es_numero_production', 'es_numero_depense', 'es_ref', 'es_designation', 'es_categorie', 'es_quantite', 'es_unite', 'es_prix_vente'].forEach(id => {
                const element = document.getElementById(id);
                if (element) element.value = '';
            });
            fillSortieProductionNumbers();
            fillSortieDepenseNumbers();
            fillSortieLookups();
            setEtatSortieReadOnly(false);
        }

        function openEtatSortie(id, readOnly) {
            loadEtatsSortie();
            const sortie = etatsSortie.find(item => String(item.id) === String(id));
            if (!sortie) return;
            editingEtatSortieId = sortie.id;
            fillSortieProductionNumbers(sortie.numero_production || '');
            fillSortieDepenseNumbers(sortie.numero_production || '', sortie.numero_depense || '');
            fillSortieLookups(sortie.categorie || '');
            document.getElementById('es_date').value = sortie.date || '';
            document.getElementById('es_numero').value = sortie.numero_sortie || '';
            document.getElementById('es_ref').value = sortie.ref || '';
            document.getElementById('es_designation').value = sortie.designation || '';
            document.getElementById('es_categorie').value = sortie.categorie || '';
            document.getElementById('es_quantite').value = sortie.quantite || '';
            document.getElementById('es_unite').value = sortie.unite || '';
            document.getElementById('es_prix_vente').value = sortie.prix_vente != null && sortie.prix_vente !== ''
                ? Number(sortie.prix_vente).toFixed(2)
                : '';
            setEtatSortieReadOnly(readOnly);
            showEtatSortieMode('saisie');
        }

        function printCurrentEtatSortie() {
            const ref = document.getElementById('es_ref')?.value || '';
            const designation = document.getElementById('es_designation')?.value || '';
            if (!ref && !designation) {
                alert('Ouvrez ou saisissez un état de sortie avant impression.');
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
            const selectedDepense = document.getElementById('es_numero_depense')?.value || '';
            fillSortieDepenseNumbers(event.target.value, selectedDepense);
        });

        document.getElementById('es_numero_depense')?.addEventListener('change', event => {
            const etat = getSortieDepenseEtat(event.target.value);
            if (!etat) return;
            const numeroProduction = etat.numero_production
                || (etat.lignes || []).map(ligne => ligne.numero_production).find(Boolean)
                || '';
            if (numeroProduction) {
                fillSortieProductionNumbers(numeroProduction);
                fillSortieDepenseNumbers(numeroProduction, etat.numero);
            }
        });

        document.getElementById('es_ref')?.addEventListener('change', event => {
            applySortieProductMatch(event.target.value, document.getElementById('es_designation')?.value || '');
        });

        document.getElementById('es_designation')?.addEventListener('change', event => {
            applySortieProductMatch(document.getElementById('es_ref')?.value || '', event.target.value);
        });

        document.getElementById('etatSortieForm')?.addEventListener('submit', event => {
            event.preventDefault();
            document.getElementById('validerEtatSortieBtn')?.click();
        });

        document.getElementById('validerEtatSortieBtn')?.addEventListener('click', event => {
            event.preventDefault();
            event.stopPropagation();
            const numeroProduction = document.getElementById('es_numero_production')?.value || '';
            const numeroDepense = document.getElementById('es_numero_depense')?.value || '';
            const ref = String(document.getElementById('es_ref')?.value || '').trim();
            const designation = String(document.getElementById('es_designation')?.value || '').trim();
            const categorie = document.getElementById('es_categorie')?.value || '';
            const unite = String(document.getElementById('es_unite')?.value || '').trim();
            const quantite = Number(document.getElementById('es_quantite')?.value || 0);
            const prixVenteRaw = document.getElementById('es_prix_vente')?.value;
            const prixVente = prixVenteRaw === '' || prixVenteRaw == null ? null : Number(prixVenteRaw);
            if (!numeroProduction) {
                alert('Sélectionnez le N° E/P.');
                return;
            }
            if (!numeroDepense) {
                alert('Sélectionnez le N° E/D.');
                return;
            }
            if (!ref && !designation) {
                alert('Saisissez une référence ou une désignation.');
                return;
            }
            if (!(quantite > 0)) {
                alert('La quantité doit être supérieure à zéro.');
                return;
            }
            if (prixVente != null && prixVente < 0) {
                alert('Le P/V ne peut pas être négatif.');
                return;
            }
            try {
                loadEtatsSortie();
                const data = {
                    id: editingEtatSortieId || ('es-' + Date.now()),
                    numero_sortie: document.getElementById('es_numero')?.value
                        || (editingEtatSortieId
                            ? (etatsSortie.find(item => String(item.id) === String(editingEtatSortieId))?.numero_sortie || nextEtatSortieNumber())
                            : nextEtatSortieNumber()),
                    date: document.getElementById('es_date')?.value || '',
                    numero_production: numeroProduction,
                    numero_depense: numeroDepense,
                    ref,
                    designation,
                    categorie,
                    quantite,
                    unite,
                    prix_vente: prixVente
                };
                const index = etatsSortie.findIndex(item => String(item.id) === String(editingEtatSortieId));
                if (index >= 0) etatsSortie[index] = data;
                else etatsSortie.push(data);
                saveEtatsSortie();
                editingEtatSortieId = null;
                renderEtatsSortieTable();
                showEtatSortieMode('consult');
                if (typeof renderDepotFiniTable === 'function') renderDepotFiniTable();
                if (typeof renderDashboardStockFinis === 'function') renderDashboardStockFinis();
                if (typeof renderProduitsTable === 'function') renderProduitsTable();
            } catch (error) {
                console.error('Validation état sortie:', error);
                alert('Impossible d\'enregistrer l\'état de sortie.');
            }
        });

        document.getElementById('imprimerEtatSortieBtn')?.addEventListener('click', printCurrentEtatSortie);

        document.getElementById('etatsSortieTableBody')?.addEventListener('click', event => {
            const button = event.target.closest('[data-sortie-action]');
            if (!button) return;
            const id = button.dataset.sortieId;
            const action = button.dataset.sortieAction;
            if (action === 'view') openEtatSortie(id, true);
            if (action === 'edit') openEtatSortie(id, false);
            if (action === 'print') {
                openEtatSortie(id, true);
                setTimeout(printCurrentEtatSortie, 100);
            }
        });
