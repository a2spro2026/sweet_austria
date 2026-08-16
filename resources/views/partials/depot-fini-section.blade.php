<style>
    #depotFiniView {
        flex: 1;
        min-height: 0;
        max-height: 100%;
        overflow: hidden;
    }
    #depotFiniView:not(.hidden),
    #depotFiniConsultPanel:not(.hidden),
    #depotFiniDetailPanel:not(.hidden) {
        display: flex;
        flex-direction: column;
    }
    #depotFiniConsultPanel,
    #depotFiniDetailPanel,
    #depotFiniPrintArea {
        flex: 1;
        min-height: 0;
        overflow: hidden;
    }
    #depotFiniConsultPanel .list-toolbar,
    #depotFiniDetailPanel .list-toolbar {
        position: relative !important;
        top: auto !important;
        order: 0;
        flex: 0 0 auto;
        width: 100%;
        min-height: 58px;
        margin: 0;
        padding: 9px 12px;
        border: 1px solid var(--border);
        border-bottom: 0;
        border-radius: 12px 12px 0 0;
        background: #fff;
        box-shadow: none;
    }
    #depotFiniPrintArea,
    #depotFiniDetailArea {
        order: 1;
        display: flex;
        flex-direction: column;
        border-radius: 0 0 12px 12px;
    }
    #depotFiniPrintArea .fournisseur-table-wrap {
        flex: 1;
        min-height: 0;
        overflow: auto;
    }
    #depotFiniDetailArea {
        padding: 20px;
        overflow: auto;
    }
    #depotFiniView .depot-fini-detail-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(180px, 1fr));
        gap: 12px;
    }
    #depotFiniView .depot-fini-detail-item {
        padding: 13px;
        border: 1px solid var(--border);
        border-radius: 9px;
        background: #fafbf9;
    }
    #depotFiniView .depot-fini-detail-item span {
        display: block;
        margin-bottom: 5px;
        color: var(--text-muted);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }
    #depotFiniView .depot-fini-detail-item strong {
        color: var(--green-dark);
        font-size: 14px;
    }
    #depotFiniView .stock-fini-zero {
        color: #9B2C2C;
        font-weight: 800;
    }
    #depotFiniView .stock-fini-positive {
        color: #177245;
        font-weight: 800;
    }
    #depotFiniView .depot-fini-print-title {
        display: none;
    }
    @media (max-width: 900px) {
        #depotFiniView .depot-fini-detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media print {
        body.print-depot-fini .sidebar,
        body.print-depot-fini .sidebar-toggle,
        body.print-depot-fini .hero-header,
        body.print-depot-fini .page-footer,
        body.print-depot-fini #depotFiniConsultPanel > .list-toolbar {
            display: none !important;
        }
        body.print-depot-fini * { visibility: hidden !important; }
        body.print-depot-fini #depotFiniPrintArea,
        body.print-depot-fini #depotFiniPrintArea * { visibility: visible !important; }
        body.print-depot-fini #depotFiniPrintArea {
            position: absolute;
            inset: 0;
            width: 100%;
            max-height: none !important;
            overflow: visible !important;
            border: 0 !important;
            box-shadow: none !important;
        }
        body.print-depot-fini #depotFiniPrintArea .fournisseur-table-wrap {
            overflow: visible !important;
        }
        body.print-depot-fini .depot-fini-print-title {
            display: block !important;
            margin: 0 0 18px;
            text-align: center;
            color: #003326;
            font-size: 24px;
        }
        body.print-depot-fini .no-print-depot-fini { display: none !important; }
    }
</style>

<div id="depotFiniView" class="saisie-panel hidden">
    <div id="depotFiniConsultPanel">
        <div class="list-toolbar no-print-depot-fini">
            <h2 class="list-toolbar-title">Dépôt Produits Finis</h2>
            <div class="list-toolbar-actions">
                <button type="button" class="btn-list btn-list-print" id="printDepotFiniBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Imprimer
                </button>
                <button type="button" class="btn-list btn-list-print" id="fermerDepotFiniBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Fermer
                </button>
            </div>
        </div>

        <div class="saisie-card" id="depotFiniPrintArea">
            <h1 class="depot-fini-print-title">DÉPÔT PRODUITS FINIS</h1>
            <div class="fournisseur-table-wrap">
                <table class="achats-commandes-table" id="depotFiniTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>N° Etat Sortie</th>
                            <th>N° Etat Production</th>
                            <th>Réf</th>
                            <th>Désignation</th>
                            <th>Qté Entrée</th>
                            <th>Qté Dépensée</th>
                            <th>Stock</th>
                            <th>U</th>
                            <th class="col-actions no-print-depot-fini">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="depotFiniTableBody">
                        <tr><td colspan="10" class="fournisseur-empty">Aucun produit fini (importé depuis Etat Sortie)</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="depotFiniDetailPanel" class="hidden">
        <div class="list-toolbar no-print-depot-fini">
            <h2 class="list-toolbar-title" id="depotFiniDetailTitle">Détail Produit Fini</h2>
            <div class="list-toolbar-actions">
                <button type="button" class="btn-list btn-list-print" id="fermerDepotFiniDetailBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Retour
                </button>
            </div>
        </div>
        <div class="saisie-card" id="depotFiniDetailArea"></div>
    </div>
</div>
