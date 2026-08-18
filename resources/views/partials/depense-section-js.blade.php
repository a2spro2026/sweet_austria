        const ETAT_DEPENSE_STORAGE_KEY = 'etatsDepense';
        const ETAT_DEPENSE_COUNTER_KEY = 'etatDepenseCounter';
        let etatsDepense = [];
        let etatDepenseLignes = [];
        let editingEtatDepenseId = null;
        let currentEtatDepenseId = null;

        function loadEtatsDepense() {
            try {
                const parsed = JSON.parse(localStorage.getItem(ETAT_DEPENSE_STORAGE_KEY) || '[]');
                etatsDepense = Array.isArray(parsed) ? parsed : [];
            } catch (error) {
                console.error('Lecture états dépense:', error);
                etatsDepense = [];
            }
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
            etatsDepense.forEach(etat => {
                const fromLine = (etat.lignes || []).map(ligne => ligne.numero_sortie || ligne.numero)
                    .find(numero => parseNumero(numero) > 0);
                const nextNumero = normalizeNumero(etat.numero || fromLine, 'ED');
                if (nextNumero && etat.numero !== nextNumero) {
                    etat.numero = nextNumero;
                    migrated = true;
                }
                if (!etat.numero) {
                    counter += 1;
                    etat.numero = formatNumero('ED', counter);
                    migrated = true;
                }
                const nextProduction = normalizeNumero(etat.numero_production, 'EP');
                if (nextProduction && etat.numero_production !== nextProduction) {
                    etat.numero_production = nextProduction;
                    migrated = true;
                }
                (etat.lignes || []).forEach(ligne => {
                    const lineProduction = normalizeNumero(ligne.numero_production, 'EP');
                    if (lineProduction && ligne.numero_production !== lineProduction) {
                        ligne.numero_production = lineProduction;
                        migrated = true;
                    }
                });
                counter = Math.max(counter, parseNumero(etat.numero));
            });
            if (!etatsDepense.length) counter = 0;
            localStorage.setItem(ETAT_DEPENSE_COUNTER_KEY, String(counter));
            if (migrated) localStorage.setItem(ETAT_DEPENSE_STORAGE_KEY, JSON.stringify(etatsDepense));
            return etatsDepense;
        }

        function saveEtatsDepense() {
            localStorage.setItem(ETAT_DEPENSE_STORAGE_KEY, JSON.stringify(etatsDepense));
        }

        function nextEtatDepenseNumber() {
            loadEtatsDepense();
            const next = Number(localStorage.getItem(ETAT_DEPENSE_COUNTER_KEY) || 0) + 1;
            localStorage.setItem(ETAT_DEPENSE_COUNTER_KEY, String(next));
            return typeof formatEtatNumero === 'function'
                ? formatEtatNumero('ED', next)
                : ('ED/' + String(next).padStart(4, '0'));
        }

        function formatDepenseNumber(value) {
            return Number(value || 0).toLocaleString('fr-FR', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 3
            });
        }

        function formatDepenseMoney(value) {
            return Number(value || 0).toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function getDepenseProductionEtats() {
            if (typeof loadEtatsProduction === 'function') loadEtatsProduction();
            return Array.isArray(etatsProduction) ? etatsProduction : [];
        }

        function fillDepenseProductionNumbers(selected = '') {
            const select = document.getElementById('ed_numero_production');
            if (!select) return;
            const numbers = [...new Set(getDepenseProductionEtats().map(etat => etat.numero).filter(Boolean))];
            select.innerHTML = '<option value="">N° E/P</option>' +
                numbers.map(numero => `<option value="${escHtml(numero)}">${escHtml(numero)}</option>`).join('');
            select.value = selected;
        }

        function clearDepenseEntry(keepProduction = false) {
            const productionValue = keepProduction
                ? (document.getElementById('ed_numero_production')?.value || '')
                : '';
            ['ed_ref', 'ed_designation', 'ed_quantite', 'ed_unite', 'ed_prix_unitaire'].forEach(id => {
                const element = document.getElementById(id);
                if (element) element.value = '';
            });
            const sousTotal = document.getElementById('ed_sous_total');
            if (sousTotal) sousTotal.value = formatDepenseMoney(0);
            fillDepenseProductionNumbers(productionValue);
        }

        function updateDepenseSousTotal() {
            const quantite = Number(document.getElementById('ed_quantite')?.value || 0);
            const prixUnitaire = Number(document.getElementById('ed_prix_unitaire')?.value || 0);
            const sousTotal = document.getElementById('ed_sous_total');
            if (sousTotal) sousTotal.value = formatDepenseMoney(quantite * prixUnitaire);
        }

        function renderEtatDepenseLignes(readOnly = false) {
            const tbody = document.getElementById('etatDepenseLignesBody');
            if (!tbody) return;
            if (!etatDepenseLignes.length) {
                tbody.innerHTML = '<tr><td colspan="8" class="fournisseur-empty">Aucune ligne ajoutée</td></tr>';
                return;
            }
            tbody.innerHTML = etatDepenseLignes.map((ligne, index) => `
                <tr>
                    <td>${escHtml(ligne.numero_production || '')}</td>
                    <td>${escHtml(ligne.ref || '')}</td>
                    <td>${escHtml(ligne.designation || '')}</td>
                    <td>${formatDepenseNumber(ligne.quantite)}</td>
                    <td>${escHtml(ligne.unite || '')}</td>
                    <td>${formatDepenseMoney(ligne.prix_unitaire)}</td>
                    <td>${formatDepenseMoney(ligne.sous_total ?? (Number(ligne.quantite) * Number(ligne.prix_unitaire)))}</td>
                    <td class="col-actions no-print-depense">
                        ${readOnly ? '—' : `<button type="button" class="btn-icon-row btn-icon-delete" data-remove-depense-line="${index}" title="Supprimer" aria-label="Supprimer">
                            <svg viewBox="0 0 24 24" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                        </button>`}
                    </td>
                </tr>
            `).join('');
        }

        function renderEtatsDepenseTable() {
            const tbody = document.getElementById('etatsDepenseTableBody');
            if (!tbody) return;
            loadEtatsDepense();
            if (!etatsDepense.length) {
                tbody.innerHTML = '<tr><td colspan="10" class="achats-commandes-empty">Aucun état dépense</td></tr>';
                return;
            }
            tbody.innerHTML = etatsDepense.slice().reverse().map(etat => {
                const lignes = Array.isArray(etat.lignes) && etat.lignes.length ? etat.lignes : [{}];
                const dateLabel = typeof formatDateFr === 'function' ? formatDateFr(etat.date) : (etat.date || '');
                return lignes.map((ligne, index) => `
                    <tr>
                        ${index === 0 ? `<td rowspan="${lignes.length}">${escHtml(dateLabel)}</td>
                        <td rowspan="${lignes.length}"><strong>${escHtml(etat.numero || '')}</strong></td>` : ''}
                        <td>${escHtml(ligne.numero_production || etat.numero_production || '')}</td>
                        <td>${escHtml(ligne.ref || '')}</td>
                        <td>${escHtml(ligne.designation || '')}</td>
                        <td>${formatDepenseNumber(ligne.quantite)}</td>
                        <td>${escHtml(ligne.unite || '')}</td>
                        <td>${formatDepenseMoney(ligne.prix_unitaire)}</td>
                        <td>${formatDepenseMoney(ligne.sous_total ?? (Number(ligne.quantite) * Number(ligne.prix_unitaire)))}</td>
                        ${index === 0 ? `<td rowspan="${lignes.length}" class="col-actions">
                            <span class="col-actions-wrap">
                                <button type="button" class="btn-icon-row btn-icon-view" data-depense-action="view" data-depense-id="${escHtml(etat.id)}" title="Voir" aria-label="Voir">
                                    <svg viewBox="0 0 24 24" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                                <button type="button" class="btn-icon-row btn-icon-edit" data-depense-action="edit" data-depense-id="${escHtml(etat.id)}" title="Modifier" aria-label="Modifier">
                                    <svg viewBox="0 0 24 24" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                </button>
                            </span>
                        </td>` : ''}
                    </tr>
                `).join('');
            }).join('');
        }

        function showEtatDepenseMode(mode) {
            document.getElementById('etatDepenseConsultMode')?.classList.toggle('hidden', mode !== 'consult');
            document.getElementById('etatDepenseSaisieMode')?.classList.toggle('hidden', mode !== 'saisie');
        }

        function setEtatDepenseReadOnly(readOnly) {
            document.querySelector('#etatDepenseView .depense-entry-stack')?.classList.toggle('hidden', readOnly);
            document.getElementById('validerEtatDepenseBtn')?.classList.toggle('hidden', readOnly);
            renderEtatDepenseLignes(readOnly);
        }

        function resetEtatDepenseForm() {
            editingEtatDepenseId = null;
            currentEtatDepenseId = null;
            etatDepenseLignes = [];
            const date = document.getElementById('ed_date');
            const numero = document.getElementById('ed_numero');
            if (date) date.value = typeof todayEtatProduction === 'function'
                ? todayEtatProduction()
                : new Date().toISOString().slice(0, 10);
            if (numero) numero.value = nextEtatDepenseNumber();
            clearDepenseEntry(false);
            setEtatDepenseReadOnly(false);
        }

        function openEtatDepense(id, readOnly = false) {
            loadEtatsDepense();
            const etat = etatsDepense.find(item => String(item.id) === String(id));
            if (!etat) return;
            editingEtatDepenseId = readOnly ? null : etat.id;
            currentEtatDepenseId = etat.id;
            etatDepenseLignes = (etat.lignes || []).map(ligne => ({ ...ligne }));
            document.getElementById('ed_date').value = etat.date || '';
            const numero = document.getElementById('ed_numero');
            if (numero) numero.value = etat.numero || '';
            clearDepenseEntry(false);
            setEtatDepenseReadOnly(readOnly);
            showEtatDepenseMode('saisie');
        }

        function printCurrentEtatDepense() {
            if (!etatDepenseLignes.length) {
                alert('Ajoutez ou ouvrez un état dépense avant impression.');
                return;
            }
            document.body.classList.add('print-etat-depense');
            const cleanup = () => {
                document.body.classList.remove('print-etat-depense');
                window.removeEventListener('afterprint', cleanup);
            };
            window.addEventListener('afterprint', cleanup);
            window.print();
            setTimeout(cleanup, 1500);
        }

        function initEtatDepenseView() {
            loadEtatsDepense();
            renderEtatsDepenseTable();
            showEtatDepenseMode('consult');
        }

        document.getElementById('nouvelEtatDepenseBtn')?.addEventListener('click', () => {
            resetEtatDepenseForm();
            showEtatDepenseMode('saisie');
        });

        document.getElementById('fermerEtatDepenseConsultBtn')?.addEventListener('click', () => {
            showAppView('dashboard');
        });

        document.getElementById('fermerEtatDepenseBtn')?.addEventListener('click', () => {
            renderEtatsDepenseTable();
            showEtatDepenseMode('consult');
        });

        function commitEtatDepenseEntry(requireComplete = true) {
            const numeroProduction = document.getElementById('ed_numero_production')?.value || '';
            const ref = String(document.getElementById('ed_ref')?.value || '').trim();
            const designation = String(document.getElementById('ed_designation')?.value || '').trim();
            const unite = String(document.getElementById('ed_unite')?.value || '').trim();
            const quantite = Number(document.getElementById('ed_quantite')?.value || 0);
            const prixUnitaire = Number(document.getElementById('ed_prix_unitaire')?.value || 0);
            const pending = !!(numeroProduction || ref || designation || quantite > 0 || document.getElementById('ed_prix_unitaire')?.value);
            if (!pending) return !requireComplete;
            if (!numeroProduction) {
                alert('Sélectionnez le N° E/P.');
                document.getElementById('ed_numero_production')?.focus();
                return false;
            }
            if (!ref && !designation) {
                alert('Saisissez une référence ou une désignation.');
                document.getElementById('ed_designation')?.focus();
                return false;
            }
            if (!(quantite > 0)) {
                alert('La quantité doit être supérieure à zéro.');
                document.getElementById('ed_quantite')?.focus();
                return false;
            }
            if (prixUnitaire < 0) {
                alert('Le prix unitaire ne peut pas être négatif.');
                document.getElementById('ed_prix_unitaire')?.focus();
                return false;
            }
            etatDepenseLignes.push({
                numero_production: numeroProduction,
                ref,
                designation,
                quantite,
                unite,
                prix_unitaire: prixUnitaire,
                sous_total: quantite * prixUnitaire
            });
            renderEtatDepenseLignes(false);
            clearDepenseEntry(true);
            return true;
        }

        document.getElementById('ed_quantite')?.addEventListener('input', updateDepenseSousTotal);
        document.getElementById('ed_prix_unitaire')?.addEventListener('input', updateDepenseSousTotal);

        document.getElementById('etatDepenseForm')?.addEventListener('submit', event => {
            event.preventDefault();
            document.getElementById('validerEtatDepenseBtn')?.click();
        });

        document.getElementById('ajouterLigneDepenseBtn')?.addEventListener('click', () => {
            commitEtatDepenseEntry(true);
        });

        document.getElementById('etatDepenseLignesBody')?.addEventListener('click', event => {
            const button = event.target.closest('[data-remove-depense-line]');
            if (!button) return;
            etatDepenseLignes.splice(Number(button.dataset.removeDepenseLine), 1);
            renderEtatDepenseLignes(false);
            clearDepenseEntry(true);
        });

        document.getElementById('validerEtatDepenseBtn')?.addEventListener('click', event => {
            event.preventDefault();
            event.stopPropagation();
            if (!commitEtatDepenseEntry(false)) return;
            if (!etatDepenseLignes.length) {
                alert('Ajoutez au moins une ligne (N° E/P, Réf, Désignation, Quantité) avant de valider.');
                return;
            }
            try {
                loadEtatsDepense();
                const data = {
                    id: editingEtatDepenseId || ('ed-' + Date.now()),
                    date: document.getElementById('ed_date')?.value || '',
                    numero: document.getElementById('ed_numero')?.value || nextEtatDepenseNumber(),
                    numero_production: etatDepenseLignes[0]?.numero_production || '',
                    lignes: etatDepenseLignes.map(ligne => ({ ...ligne }))
                };
                const index = etatsDepense.findIndex(item => String(item.id) === String(editingEtatDepenseId));
                if (index >= 0) etatsDepense[index] = data;
                else etatsDepense.push(data);
                saveEtatsDepense();
                editingEtatDepenseId = null;
                currentEtatDepenseId = null;
                etatDepenseLignes = [];
                renderEtatsDepenseTable();
                showEtatDepenseMode('consult');
                if (typeof renderDepotFiniTable === 'function') renderDepotFiniTable();
                if (typeof renderDashboardStockFinis === 'function') renderDashboardStockFinis();
            } catch (error) {
                console.error('Validation état dépense:', error);
                alert('Impossible d\'enregistrer l\'état dépense.');
            }
        });

        document.getElementById('imprimerEtatDepenseBtn')?.addEventListener('click', printCurrentEtatDepense);

        document.getElementById('etatsDepenseTableBody')?.addEventListener('click', event => {
            const button = event.target.closest('[data-depense-action]');
            if (!button) return;
            const id = button.dataset.depenseId;
            const action = button.dataset.depenseAction;
            if (action === 'view') openEtatDepense(id, true);
            if (action === 'edit') openEtatDepense(id, false);
        });
