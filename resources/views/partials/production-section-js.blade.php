        const ETAT_PRODUCTION_STORAGE_KEY = 'etatsProduction';
        const ETAT_PRODUCTION_COUNTER_KEY = 'etatProductionCounter';
        let etatsProduction = [];
        let etatProductionLignes = [];
        let editingEtatProductionId = null;
        // État ouvert dans le panneau : ses quantités sont portées par etatProductionLignes,
        // il ne faut donc pas les déduire une seconde fois depuis les états enregistrés.
        let currentEtatProductionId = null;

        function loadEtatsProduction() {
            try {
                const saved = JSON.parse(localStorage.getItem(ETAT_PRODUCTION_STORAGE_KEY) || '[]');
                etatsProduction = Array.isArray(saved) ? saved : [];
            } catch (e) {
                etatsProduction = [];
            }
            return etatsProduction;
        }

        function saveEtatsProduction() {
            localStorage.setItem(ETAT_PRODUCTION_STORAGE_KEY, JSON.stringify(etatsProduction));
        }

        function nextEtatProductionNumber() {
            let counter = Number(localStorage.getItem(ETAT_PRODUCTION_COUNTER_KEY) || 0);
            const used = etatsProduction.reduce((max, etat) => {
                const match = String(etat.numero || '').match(/(\d+)$/);
                return Math.max(max, match ? Number(match[1]) : 0);
            }, 0);
            counter = Math.max(counter, used) + 1;
            localStorage.setItem(ETAT_PRODUCTION_COUNTER_KEY, String(counter));
            return 'EP' + String(counter).padStart(4, '0');
        }

        function todayEtatProduction() {
            const now = new Date();
            const local = new Date(now.getTime() - now.getTimezoneOffset() * 60000);
            return local.toISOString().slice(0, 10);
        }

        function formatProductionNumber(value) {
            return Number(value || 0).toLocaleString('fr-FR', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 3
            });
        }

        function productionArticleKey(ref, designation) {
            const key = String(ref || '').trim() || String(designation || '').trim();
            return key.toLowerCase();
        }

        /** Quantités entrées au dépôt Produits Crus, avant toute production. */
        function getProductionDepotArticles() {
            const articles = new Map();

            if (typeof collectDepotArticlesFromAchats === 'function') {
                collectDepotArticlesFromAchats('cru').forEach(row => {
                    const key = productionArticleKey(row.ref, row.designation);
                    if (!key) return;
                    const entry = articles.get(key) || {
                        ref: String(row.ref || '').trim(),
                        designation: String(row.designation || '').trim(),
                        quantite: 0,
                        unite: String(row.ligne?.unite || row.ligne?.mesure || row.ligne?.unite_mesure || '').trim(),
                    };
                    if (!entry.ref) entry.ref = String(row.ref || '').trim();
                    if (!entry.designation) entry.designation = String(row.designation || '').trim();
                    if (!entry.unite) {
                        entry.unite = String(row.ligne?.unite || row.ligne?.mesure || row.ligne?.unite_mesure || '').trim();
                    }
                    entry.quantite += Number(row.quantite) || 0;
                    articles.set(key, entry);
                });
            }

            if (!articles.size && Array.isArray(produits)) {
                produits
                    .filter(product => String(product?.type || '').trim().toLowerCase() === 'pro cru')
                    .forEach(product => {
                        const key = productionArticleKey(product.ref, product.designation);
                        if (!key) return;
                        articles.set(key, {
                            ref: String(product.ref || '').trim(),
                            designation: String(product.designation || '').trim(),
                            quantite: Number(product.quantite) || 0,
                            unite: String(product.unite || '').trim(),
                        });
                    });
            }

            return articles;
        }

        /**
         * Stock disponible par article : entrées du dépôt moins les états déjà
         * enregistrés, moins les quantités prises dans l'état en cours de saisie.
         * `ignoreLigneIndex` permet d'exclure une ligne du brouillon du calcul.
         */
        function getProductionProducts(ignoreLigneIndex = null) {
            const articles = getProductionDepotArticles();

            loadEtatsProduction();
            etatsProduction.forEach(etat => {
                if (currentEtatProductionId && String(etat.id) === String(currentEtatProductionId)) return;
                (etat.lignes || []).forEach(ligne => {
                    const entry = articles.get(productionArticleKey(ligne.ref, ligne.designation));
                    if (entry) entry.quantite -= Number(ligne.quantite) || 0;
                });
            });

            etatProductionLignes.forEach((ligne, index) => {
                if (ignoreLigneIndex !== null && index === ignoreLigneIndex) return;
                const entry = articles.get(productionArticleKey(ligne.ref, ligne.designation));
                if (entry) entry.quantite -= Number(ligne.quantite) || 0;
            });

            return [...articles.values()];
        }

        function getProductionAvailableStock(ref, designation, ignoreLigneIndex = null) {
            const key = productionArticleKey(ref, designation);
            const article = getProductionProducts(ignoreLigneIndex)
                .find(item => productionArticleKey(item.ref, item.designation) === key);
            return article ? article.quantite : 0;
        }

        /** Réaffiche le stock disponible pour l'article actuellement sélectionné. */
        function refreshEtatProductionStockField() {
            const stock = document.getElementById('ep_stock');
            if (!stock) return;
            const ref = document.getElementById('ep_ref')?.value || '';
            const designation = document.getElementById('ep_designation')?.value || '';
            if (!ref && !designation) {
                stock.value = '0';
                return;
            }
            stock.value = formatProductionNumber(getProductionAvailableStock(ref, designation));
        }

        function findProductionProduct(ref, designation) {
            const normalizedRef = String(ref || '').trim().toLowerCase();
            const normalizedDesignation = String(designation || '').trim().toLowerCase();
            return getProductionProducts().find(product =>
                (normalizedRef && String(product.ref || '').trim().toLowerCase() === normalizedRef)
                || (normalizedDesignation && String(product.designation || '').trim().toLowerCase() === normalizedDesignation)
            ) || null;
        }

        function updateEtatProductionProductLists() {
            const refs = document.getElementById('ep_ref');
            const designations = document.getElementById('ep_designation');
            const productList = getProductionProducts();
            const selectedRef = refs?.value || '';
            const selectedDesignation = designations?.value || '';
            if (refs) {
                refs.innerHTML = '<option value="">Réf</option>' + productList
                    .filter(product => product.ref)
                    .map(product => `<option value="${escHtml(product.ref)}">${escHtml(product.ref)}</option>`)
                    .join('');
                refs.value = selectedRef;
            }
            if (designations) {
                designations.innerHTML = '<option value="">Désignation</option>' + productList
                    .filter(product => product.designation)
                    .map(product => `<option value="${escHtml(product.designation)}">${escHtml(product.designation)}</option>`)
                    .join('');
                designations.value = selectedDesignation;
            }
        }

        function applyEtatProductionProduct(product) {
            const ref = document.getElementById('ep_ref');
            const designation = document.getElementById('ep_designation');
            const stock = document.getElementById('ep_stock');
            if (!product) {
                if (stock) stock.value = '0';
                return;
            }
            if (ref) ref.value = product.ref || '';
            if (designation) designation.value = product.designation || '';
            if (stock) stock.value = formatProductionNumber(product.quantite);
        }

        function clearEtatProductionEntry() {
            ['ep_ref', 'ep_designation', 'ep_quantite'].forEach(id => {
                const input = document.getElementById(id);
                if (input) input.value = '';
            });
            const stock = document.getElementById('ep_stock');
            if (stock) stock.value = '0';
        }

        function renderEtatProductionLignes(readOnly = false) {
            const tbody = document.getElementById('etatProductionLignesBody');
            if (!tbody) return;
            if (!etatProductionLignes.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="fournisseur-empty">Aucun produit ajouté</td></tr>';
                return;
            }
            const restants = new Map(getProductionProducts().map(article =>
                [productionArticleKey(article.ref, article.designation), article.quantite]
            ));
            tbody.innerHTML = etatProductionLignes.map((ligne, index) => `
                <tr>
                    <td>${escHtml(ligne.ref || '')}</td>
                    <td>${escHtml(ligne.designation || '')}</td>
                    <td>${formatProductionNumber(restants.get(productionArticleKey(ligne.ref, ligne.designation)) ?? ligne.stock)}</td>
                    <td>${formatProductionNumber(ligne.quantite)}</td>
                    <td class="col-actions no-print-production">
                        ${readOnly ? '—' : `<button type="button" class="btn-icon-row btn-icon-delete" data-remove-production-line="${index}" title="Supprimer" aria-label="Supprimer">
                            <svg viewBox="0 0 24 24" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                        </button>`}
                    </td>
                </tr>
            `).join('');
        }

        function renderEtatsProductionTable() {
            const tbody = document.getElementById('etatsProductionTableBody');
            if (!tbody) return;
            loadEtatsProduction();
            if (!etatsProduction.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="achats-commandes-empty">Aucun état de production</td></tr>';
                return;
            }
            tbody.innerHTML = etatsProduction.slice().reverse().map(etat => {
                const lignes = Array.isArray(etat.lignes) && etat.lignes.length
                    ? etat.lignes
                    : [{ ref: '', designation: '', quantite: 0 }];
                return lignes.map((ligne, index) => `
                    <tr>
                        ${index === 0 ? `<td rowspan="${lignes.length}">${escHtml(etat.date || '')}</td>
                        <td rowspan="${lignes.length}">${escHtml(etat.numero || '')}</td>` : ''}
                        <td>${escHtml(ligne.ref || '')}</td>
                        <td>${escHtml(ligne.designation || '')}</td>
                        <td>${formatProductionNumber(ligne.quantite)}</td>
                        ${index === 0 ? `<td rowspan="${lignes.length}">
                            <div class="production-actions col-actions-wrap">
                                <button type="button" class="btn-icon-row btn-icon-view" data-production-action="view" data-production-id="${escHtml(etat.id)}" title="Voir" aria-label="Voir">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                                <button type="button" class="btn-icon-row btn-icon-edit" data-production-action="edit" data-production-id="${escHtml(etat.id)}" title="Modifier" aria-label="Modifier">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                </button>
                                <button type="button" class="btn-icon-row btn-icon-delete" data-production-action="delete" data-production-id="${escHtml(etat.id)}" title="Supprimer" aria-label="Supprimer">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                </button>
                                <button type="button" class="btn-icon-row btn-icon-print" data-production-action="print" data-production-id="${escHtml(etat.id)}" title="Imprimer" aria-label="Imprimer">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                </button>
                            </div>
                        </td>` : ''}
                    </tr>
                `).join('');
            }).join('');
        }

        function setEtatProductionReadOnly(readOnly) {
            const entry = document.querySelector('#etatProductionView .production-entry-row');
            if (entry) entry.classList.toggle('hidden', readOnly);
            const validate = document.getElementById('validerEtatProductionBtn');
            if (validate) validate.classList.toggle('hidden', readOnly);
            renderEtatProductionLignes(readOnly);
        }

        function resetEtatProductionForm() {
            editingEtatProductionId = null;
            currentEtatProductionId = null;
            etatProductionLignes = [];
            const date = document.getElementById('ep_date');
            const numero = document.getElementById('ep_numero');
            if (date) date.value = todayEtatProduction();
            if (numero) numero.value = nextEtatProductionNumber();
            clearEtatProductionEntry();
            setEtatProductionReadOnly(false);
        }

        function showEtatProductionMode(mode) {
            const consult = document.getElementById('etatProductionConsultMode');
            const form = document.getElementById('etatProductionSaisieMode');
            if (consult) consult.classList.toggle('hidden', mode !== 'consult');
            if (form) form.classList.toggle('hidden', mode !== 'saisie');
        }

        function openEtatProduction(id, readOnly = false) {
            loadEtatsProduction();
            const etat = etatsProduction.find(item => String(item.id) === String(id));
            if (!etat) return;
            editingEtatProductionId = readOnly ? null : etat.id;
            currentEtatProductionId = etat.id;
            etatProductionLignes = Array.isArray(etat.lignes) ? etat.lignes.map(ligne => ({ ...ligne })) : [];
            document.getElementById('ep_date').value = etat.date || '';
            document.getElementById('ep_numero').value = etat.numero || '';
            clearEtatProductionEntry();
            setEtatProductionReadOnly(readOnly);
            showEtatProductionMode('saisie');
        }

        function printCurrentEtatProduction() {
            document.body.classList.add('print-etat-production');
            const cleanup = () => {
                document.body.classList.remove('print-etat-production');
                window.removeEventListener('afterprint', cleanup);
            };
            window.addEventListener('afterprint', cleanup);
            window.print();
            setTimeout(cleanup, 1500);
        }

        function initEtatProductionView() {
            loadEtatsProduction();
            renderEtatsProductionTable();
            updateEtatProductionProductLists();
            showEtatProductionMode('consult');
            Promise.resolve(typeof loadProduits === 'function' ? loadProduits() : null)
                .then(updateEtatProductionProductLists)
                .catch(error => console.error('Chargement produits production:', error));
        }

        document.getElementById('nouvelEtatProductionBtn')?.addEventListener('click', () => {
            resetEtatProductionForm();
            updateEtatProductionProductLists();
            showEtatProductionMode('saisie');
            if (!getProductionProducts().length) {
                Promise.resolve(typeof loadProduits === 'function' ? loadProduits() : null)
                    .then(updateEtatProductionProductLists)
                    .catch(error => console.error('Chargement produits production:', error));
            }
        });

        document.getElementById('fermerEtatProductionConsultBtn')?.addEventListener('click', () => {
            showAppView('dashboard');
        });

        document.getElementById('fermerEtatProductionBtn')?.addEventListener('click', () => {
            renderEtatsProductionTable();
            showEtatProductionMode('consult');
        });

        document.getElementById('ep_ref')?.addEventListener('change', event => {
            applyEtatProductionProduct(findProductionProduct(event.target.value, ''));
        });

        document.getElementById('ep_designation')?.addEventListener('change', event => {
            applyEtatProductionProduct(findProductionProduct('', event.target.value));
        });

        document.getElementById('ajouterLigneProductionBtn')?.addEventListener('click', () => {
            const ref = document.getElementById('ep_ref')?.value.trim() || '';
            const designation = document.getElementById('ep_designation')?.value.trim() || '';
            const product = findProductionProduct(ref, designation);
            const quantite = Number(document.getElementById('ep_quantite')?.value || 0);
            if (!product) {
                alert('Sélectionnez un produit existant.');
                document.getElementById('ep_designation')?.focus();
                return;
            }
            if (!(quantite > 0)) {
                alert('La quantité doit être supérieure à zéro.');
                document.getElementById('ep_quantite')?.focus();
                return;
            }
            const disponible = getProductionAvailableStock(product.ref, product.designation);
            if (quantite > disponible) {
                alert('Quantité supérieure au stock disponible (' + formatProductionNumber(disponible) + ').');
                document.getElementById('ep_quantite')?.focus();
                return;
            }
            const existing = etatProductionLignes.find(ligne =>
                productionArticleKey(ligne.ref, ligne.designation) === productionArticleKey(product.ref, product.designation)
            );
            if (existing) {
                existing.quantite = Number(existing.quantite || 0) + quantite;
            } else {
                etatProductionLignes.push({
                    ref: product.ref || '',
                    designation: product.designation || '',
                    unite: product.unite || '',
                    quantite
                });
            }
            renderEtatProductionLignes(false);
            clearEtatProductionEntry();
            refreshEtatProductionStockField();
        });

        document.getElementById('etatProductionLignesBody')?.addEventListener('click', event => {
            const button = event.target.closest('[data-remove-production-line]');
            if (!button) return;
            etatProductionLignes.splice(Number(button.dataset.removeProductionLine), 1);
            renderEtatProductionLignes(false);
            refreshEtatProductionStockField();
        });

        document.getElementById('validerEtatProductionBtn')?.addEventListener('click', () => {
            if (!etatProductionLignes.length) {
                alert('Ajoutez au moins un produit.');
                return;
            }
            loadEtatsProduction();
            const data = {
                id: editingEtatProductionId || ('ep-' + Date.now()),
                date: document.getElementById('ep_date')?.value || todayEtatProduction(),
                numero: document.getElementById('ep_numero')?.value || nextEtatProductionNumber(),
                lignes: etatProductionLignes.map(ligne => ({ ...ligne }))
            };
            const index = etatsProduction.findIndex(item => String(item.id) === String(editingEtatProductionId));
            if (index >= 0) etatsProduction[index] = data;
            else etatsProduction.push(data);
            saveEtatsProduction();
            editingEtatProductionId = data.id;
            currentEtatProductionId = data.id;
            renderEtatsProductionTable();
            renderEtatProductionLignes(false);
            refreshEtatProductionStockField();
            alert('Etat de production enregistré.');
        });

        document.getElementById('imprimerEtatProductionBtn')?.addEventListener('click', () => {
            if (!etatProductionLignes.length) {
                alert('Ajoutez ou ouvrez un état de production avant impression.');
                return;
            }
            printCurrentEtatProduction();
        });

        document.getElementById('etatsProductionTableBody')?.addEventListener('click', event => {
            const button = event.target.closest('[data-production-action]');
            if (!button) return;
            const id = button.dataset.productionId;
            const action = button.dataset.productionAction;
            if (action === 'view') openEtatProduction(id, true);
            if (action === 'edit') openEtatProduction(id, false);
            if (action === 'delete') {
                if (!confirm('Supprimer cet état de production ?')) return;
                loadEtatsProduction();
                etatsProduction = etatsProduction.filter(item => String(item.id) !== String(id));
                saveEtatsProduction();
                renderEtatsProductionTable();
            }
            if (action === 'print') {
                openEtatProduction(id, true);
                setTimeout(printCurrentEtatProduction, 100);
            }
        });
