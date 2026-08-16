        let depotFiniRows = [];

        function collectDepotFiniFromSorties() {
            if (typeof loadEtatsSortie === 'function') loadEtatsSortie();
            if (typeof loadEtatsDepense === 'function') loadEtatsDepense();
            const sorties = Array.isArray(etatsSortie) ? etatsSortie : [];
            const depenses = Array.isArray(etatsDepense) ? etatsDepense : [];

            const spentBySortie = new Map();
            depenses.forEach(etat => {
                (etat.lignes || []).forEach(ligne => {
                    const numero = String(ligne.numero_sortie || '');
                    if (!numero) return;
                    spentBySortie.set(numero,
                        (spentBySortie.get(numero) || 0) + (Number(ligne.quantite) || 0)
                    );
                });
            });

            return sorties.map(sortie => {
                const entree = Number(sortie.quantite) || 0;
                const depensee = spentBySortie.get(String(sortie.numero_sortie || '')) || 0;
                return {
                    id: sortie.id,
                    date: sortie.date || '',
                    numero_sortie: sortie.numero_sortie || '',
                    numero_production: sortie.numero_production || '',
                    ref: sortie.ref || '',
                    designation: sortie.designation || '',
                    quantite_entree: entree,
                    quantite_depensee: depensee,
                    stock: Math.max(0, entree - depensee),
                    unite: sortie.unite || '',
                    sortie
                };
            }).sort((a, b) =>
                String(b.date || '').localeCompare(String(a.date || ''))
                || String(b.numero_sortie || '').localeCompare(String(a.numero_sortie || ''))
            );
        }

        function renderDepotFiniTable() {
            const tbody = document.getElementById('depotFiniTableBody');
            if (!tbody) return;
            depotFiniRows = collectDepotFiniFromSorties();
            if (!depotFiniRows.length) {
                tbody.innerHTML = '<tr><td colspan="10" class="fournisseur-empty">Aucun produit fini (importé depuis Etat Sortie)</td></tr>';
                return;
            }
            tbody.innerHTML = depotFiniRows.map(row => `
                <tr>
                    <td>${escHtml(typeof formatDateFr === 'function' ? formatDateFr(row.date) : row.date)}</td>
                    <td><strong>${escHtml(row.numero_sortie)}</strong></td>
                    <td>${escHtml(row.numero_production)}</td>
                    <td>${escHtml(row.ref)}</td>
                    <td>${escHtml(row.designation)}</td>
                    <td>${formatDepenseNumber(row.quantite_entree)}</td>
                    <td>${formatDepenseNumber(row.quantite_depensee)}</td>
                    <td class="${row.stock > 0 ? 'stock-fini-positive' : 'stock-fini-zero'}">${formatDepenseNumber(row.stock)}</td>
                    <td>${escHtml(row.unite)}</td>
                    <td class="col-actions no-print-depot-fini">
                        <span class="col-actions-wrap">
                            <button type="button" class="btn-icon-row btn-icon-view" data-depot-fini-view="${escHtml(row.id)}" title="Voir" aria-label="Voir">
                                <svg viewBox="0 0 24 24" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </span>
                    </td>
                </tr>
            `).join('');
        }

        function renderDashboardStockFinis() {
            const tbody = document.getElementById('dashStockFinisBody');
            if (!tbody) return;
            const grouped = new Map();
            collectDepotFiniFromSorties().forEach(row => {
                const key = typeof productionArticleKey === 'function'
                    ? productionArticleKey(row.ref, row.designation)
                    : (row.ref || row.designation).toLowerCase();
                const item = grouped.get(key) || {
                    ref: row.ref,
                    designation: row.designation,
                    stock: 0,
                    unite: row.unite
                };
                item.stock += row.stock;
                grouped.set(key, item);
            });
            const rows = [...grouped.values()];
            if (!rows.length) {
                tbody.innerHTML = '<tr><td colspan="4" class="fournisseur-empty" style="text-align:center;">Aucun stock produit fini</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(row => `
                <tr>
                    <td>${escHtml(row.ref || '—')}</td>
                    <td>${escHtml(row.designation || '—')}</td>
                    <td>${formatDepenseNumber(row.stock)}${row.unite ? ' ' + escHtml(row.unite) : ''}</td>
                    <td><span class="${row.stock > 0 ? 'status-dispo' : 'status-rupture'}">${row.stock > 0 ? 'Disponible' : 'Épuisé'}</span></td>
                </tr>
            `).join('');
        }

        function showDepotFiniConsult() {
            document.getElementById('depotFiniConsultPanel')?.classList.remove('hidden');
            document.getElementById('depotFiniDetailPanel')?.classList.add('hidden');
            renderDepotFiniTable();
        }

        function initDepotFiniView() {
            showDepotFiniConsult();
            renderDashboardStockFinis();
        }

        function openDepotFiniDetail(id) {
            renderDepotFiniTable();
            const row = depotFiniRows.find(item => String(item.id) === String(id));
            if (!row) return;
            const title = document.getElementById('depotFiniDetailTitle');
            const area = document.getElementById('depotFiniDetailArea');
            if (title) title.textContent = 'Détail Produit Fini — ' + (row.designation || row.ref || '');
            if (area) {
                const details = [
                    ['Date', typeof formatDateFr === 'function' ? formatDateFr(row.date) : row.date],
                    ['N° Etat Sortie', row.numero_sortie],
                    ['N° Etat Production', row.numero_production],
                    ['Référence', row.ref],
                    ['Désignation', row.designation],
                    ['Quantité entrée', formatDepenseNumber(row.quantite_entree) + (row.unite ? ' ' + row.unite : '')],
                    ['Quantité dépensée', formatDepenseNumber(row.quantite_depensee) + (row.unite ? ' ' + row.unite : '')],
                    ['Stock disponible', formatDepenseNumber(row.stock) + (row.unite ? ' ' + row.unite : '')],
                    ['Unité', row.unite]
                ];
                area.innerHTML = '<div class="depot-fini-detail-grid">' + details.map(([label, value]) => `
                    <div class="depot-fini-detail-item">
                        <span>${escHtml(label)}</span>
                        <strong>${escHtml(value || '—')}</strong>
                    </div>
                `).join('') + '</div>';
            }
            document.getElementById('depotFiniConsultPanel')?.classList.add('hidden');
            document.getElementById('depotFiniDetailPanel')?.classList.remove('hidden');
        }

        document.getElementById('depotFiniTableBody')?.addEventListener('click', event => {
            const button = event.target.closest('[data-depot-fini-view]');
            if (button) openDepotFiniDetail(button.dataset.depotFiniView);
        });

        document.getElementById('fermerDepotFiniDetailBtn')?.addEventListener('click', showDepotFiniConsult);

        document.getElementById('fermerDepotFiniBtn')?.addEventListener('click', () => {
            showAppView('dashboard');
        });

        document.getElementById('printDepotFiniBtn')?.addEventListener('click', () => {
            if (!collectDepotFiniFromSorties().length) {
                alert('Aucun produit fini à imprimer.');
                return;
            }
            showDepotFiniConsult();
            document.body.classList.add('print-depot-fini');
            const cleanup = () => {
                document.body.classList.remove('print-depot-fini');
                window.removeEventListener('afterprint', cleanup);
            };
            window.addEventListener('afterprint', cleanup);
            window.print();
            setTimeout(cleanup, 1500);
        });

        renderDashboardStockFinis();
