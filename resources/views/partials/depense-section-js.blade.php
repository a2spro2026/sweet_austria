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
            return etatsDepense;
        }

        function saveEtatsDepense() {
            localStorage.setItem(ETAT_DEPENSE_STORAGE_KEY, JSON.stringify(etatsDepense));
        }

        function peekNextEtatDepenseNumber() {
            const used = [
                ...loadEtatsDepense().flatMap(etat => (etat.lignes || []).map(ligne => ligne.numero_sortie)),
                ...etatDepenseLignes.map(ligne => ligne.numero_sortie)
            ];
            let counter = Math.max(
                Number(localStorage.getItem(ETAT_DEPENSE_COUNTER_KEY) || 0),
                ...used.map(numero => {
                    const match = String(numero || '').match(/^ED(\d+)$/i);
                    return match ? Number(match[1]) : 0;
                })
            );
            return 'ED' + String(counter + 1).padStart(4, '0');
        }

        function nextEtatDepenseNumber() {
            const next = peekNextEtatDepenseNumber();
            const match = next.match(/(\d+)$/);
            localStorage.setItem(ETAT_DEPENSE_COUNTER_KEY, String(match ? Number(match[1]) : 0));
            return next;
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
            select.innerHTML = '<option value="">N° Etat Production</option>' +
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
            const numeroSortie = document.getElementById('ed_numero_sortie');
            if (numeroSortie) numeroSortie.value = peekNextEtatDepenseNumber();
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
                tbody.innerHTML = '<tr><td colspan="9" class="fournisseur-empty">Aucune ligne ajoutée</td></tr>';
                return;
            }
            tbody.innerHTML = etatDepenseLignes.map((ligne, index) => `
                <tr>
                    <td>${escHtml(ligne.numero_sortie || '')}</td>
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
                tbody.innerHTML = '<tr><td colspan="9" class="achats-commandes-empty">Aucun état dépense</td></tr>';
                return;
            }
            tbody.innerHTML = etatsDepense.slice().reverse().map(etat => {
                const lignes = Array.isArray(etat.lignes) && etat.lignes.length ? etat.lignes : [{}];
                return lignes.map((ligne, index) => `
                    <tr>
                        <td>${escHtml(ligne.numero_sortie || '')}</td>
                        <td>${escHtml(ligne.numero_production || '')}</td>
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
                                <button type="button" class="btn-icon-row btn-icon-delete" data-depense-action="delete" data-depense-id="${escHtml(etat.id)}" title="Supprimer" aria-label="Supprimer">
                                    <svg viewBox="0 0 24 24" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                </button>
                                <button type="button" class="btn-icon-row btn-icon-print" data-depense-action="print" data-depense-id="${escHtml(etat.id)}" title="Imprimer" aria-label="Imprimer">
                                    <svg viewBox="0 0 24 24" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
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
            document.querySelector('#etatDepenseView .depense-entry-row')?.classList.toggle('hidden', readOnly);
            document.getElementById('validerEtatDepenseBtn')?.classList.toggle('hidden', readOnly);
            renderEtatDepenseLignes(readOnly);
        }

        function resetEtatDepenseForm() {
            editingEtatDepenseId = null;
            currentEtatDepenseId = null;
            etatDepenseLignes = [];
            const date = document.getElementById('ed_date');
            if (date) date.value = typeof todayEtatProduction === 'function'
                ? todayEtatProduction()
                : new Date().toISOString().slice(0, 10);
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

        document.getElementById('ed_quantite')?.addEventListener('input', updateDepenseSousTotal);
        document.getElementById('ed_prix_unitaire')?.addEventListener('input', updateDepenseSousTotal);

        document.getElementById('ajouterLigneDepenseBtn')?.addEventListener('click', () => {
            const numeroProduction = document.getElementById('ed_numero_production')?.value || '';
            const ref = String(document.getElementById('ed_ref')?.value || '').trim();
            const designation = String(document.getElementById('ed_designation')?.value || '').trim();
            const unite = String(document.getElementById('ed_unite')?.value || '').trim();
            const quantite = Number(document.getElementById('ed_quantite')?.value || 0);
            const prixUnitaire = Number(document.getElementById('ed_prix_unitaire')?.value || 0);
            if (!numeroProduction) {
                alert('Sélectionnez le N° Etat Production.');
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
            if (prixUnitaire < 0) {
                alert('Le prix unitaire ne peut pas être négatif.');
                return;
            }
            etatDepenseLignes.push({
                numero_sortie: nextEtatDepenseNumber(),
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
        });

        document.getElementById('etatDepenseLignesBody')?.addEventListener('click', event => {
            const button = event.target.closest('[data-remove-depense-line]');
            if (!button) return;
            etatDepenseLignes.splice(Number(button.dataset.removeDepenseLine), 1);
            renderEtatDepenseLignes(false);
            clearDepenseEntry(true);
        });

        document.getElementById('validerEtatDepenseBtn')?.addEventListener('click', () => {
            if (!etatDepenseLignes.length) {
                alert('Ajoutez au moins une ligne.');
                return;
            }
            loadEtatsDepense();
            const data = {
                id: editingEtatDepenseId || ('ed-' + Date.now()),
                date: document.getElementById('ed_date')?.value || '',
                lignes: etatDepenseLignes.map(ligne => ({ ...ligne }))
            };
            const index = etatsDepense.findIndex(item => String(item.id) === String(editingEtatDepenseId));
            if (index >= 0) etatsDepense[index] = data;
            else etatsDepense.push(data);
            saveEtatsDepense();
            editingEtatDepenseId = data.id;
            currentEtatDepenseId = data.id;
            renderEtatsDepenseTable();
            if (typeof renderDepotFiniTable === 'function') renderDepotFiniTable();
            if (typeof renderDashboardStockFinis === 'function') renderDashboardStockFinis();
            alert('Etat dépense enregistré.');
        });

        document.getElementById('imprimerEtatDepenseBtn')?.addEventListener('click', printCurrentEtatDepense);

        document.getElementById('etatsDepenseTableBody')?.addEventListener('click', event => {
            const button = event.target.closest('[data-depense-action]');
            if (!button) return;
            const id = button.dataset.depenseId;
            const action = button.dataset.depenseAction;
            if (action === 'view') openEtatDepense(id, true);
            if (action === 'edit') openEtatDepense(id, false);
            if (action === 'delete') {
                if (!confirm('Supprimer cet état dépense ?')) return;
                loadEtatsDepense();
                etatsDepense = etatsDepense.filter(item => String(item.id) !== String(id));
                saveEtatsDepense();
                renderEtatsDepenseTable();
                if (typeof renderDepotFiniTable === 'function') renderDepotFiniTable();
                if (typeof renderDashboardStockFinis === 'function') renderDashboardStockFinis();
            }
            if (action === 'print') {
                openEtatDepense(id, true);
                setTimeout(printCurrentEtatDepense, 100);
            }
        });
