<style>
    #etatSortieView,
    #etatSortieView:not(.hidden),
    #etatSortieConsultMode:not(.hidden),
    #etatSortieSaisieMode:not(.hidden) {
        min-height: 0;
    }
    #etatSortieView:not(.hidden),
    #etatSortieConsultMode:not(.hidden),
    #etatSortieSaisieMode:not(.hidden) {
        display: flex;
        flex-direction: column;
    }
    #etatSortieView {
        flex: 1;
        max-height: 100%;
        overflow: hidden;
    }
    #etatSortieConsultMode,
    #etatSortieListArea {
        flex: 1;
        min-height: 0;
        overflow: hidden;
    }
    #etatSortieConsultMode .list-toolbar {
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
    #etatSortieListArea {
        order: 1;
        display: flex;
        flex-direction: column;
        border-radius: 0 0 12px 12px;
    }
    #etatSortieListArea .fournisseur-table-wrap {
        flex: 1;
        min-height: 0;
        overflow: auto;
    }
    #etatSortieSaisieMode {
        max-height: calc(100vh - var(--header-h) - 96px);
    }
    #etatSortieSaisieMode .saisie-form {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
        padding: 0;
    }
    #etatSortieFormArea {
        flex: 1;
        min-height: 0;
        overflow: auto;
        padding: 18px;
    }
    #etatSortieView .sortie-form-grid {
        display: grid;
        grid-template-columns: 140px 180px 130px minmax(220px, 1fr) 120px 110px;
        gap: 10px;
        align-items: end;
    }
    #etatSortieView .sortie-form-grid .form-group {
        min-width: 0;
        margin: 0;
    }
    #etatSortieView #es_numero_production,
    #etatSortieView #es_ref,
    #etatSortieView #es_designation {
        cursor: pointer;
        padding-right: 26px;
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23004236' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 13px;
    }
    #etatSortieView #es_unite {
        background: #edf5f1;
        font-weight: 700;
    }
    #etatSortieView .sortie-form-actions {
        flex: 0 0 auto;
        margin: 0;
        padding: 12px 18px 16px;
        border-top: 1px solid var(--border);
        background: #fff;
    }
    #etatSortieView .sortie-print-title {
        display: none;
    }
    @media (max-width: 1100px) {
        #etatSortieView .sortie-form-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    @media (max-width: 720px) {
        #etatSortieView .sortie-form-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media print {
        body.print-etat-sortie .sidebar,
        body.print-etat-sortie .sidebar-toggle,
        body.print-etat-sortie .hero-header,
        body.print-etat-sortie .page-footer,
        body.print-etat-sortie #etatSortieConsultMode > .list-toolbar {
            display: none !important;
        }
        body.print-etat-sortie * { visibility: hidden !important; }
        body.print-etat-sortie #etatSortieListArea,
        body.print-etat-sortie #etatSortieListArea * { visibility: visible !important; }
        body.print-etat-sortie #etatSortieListArea {
            position: absolute;
            inset: 0;
            width: 100%;
            max-height: none !important;
            overflow: visible !important;
            border: 0 !important;
            box-shadow: none !important;
        }
        body.print-etat-sortie #etatSortieListArea .sortie-print-title {
            display: block !important;
            visibility: visible !important;
            margin: 0 0 18px;
            text-align: center;
            color: #003326;
            font-size: 24px;
        }
        body.print-etat-sortie #etatSortieListArea .fournisseur-table-wrap {
            overflow: visible !important;
        }
        body.print-etat-sortie .no-print-sortie { display: none !important; }
    }
</style>

<div id="etatSortieView" class="saisie-panel hidden">
    <div id="etatSortieConsultMode">
        <div class="list-toolbar">
            <h2 class="list-toolbar-title">Etat Sortie</h2>
            <div class="list-toolbar-actions">
                <button type="button" class="btn-list btn-list-add" id="nouvelEtatSortieBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Ajouter
                </button>
                <button type="button" class="btn-list btn-list-print" id="imprimerEtatsSortieBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Imprimer
                </button>
                <button type="button" class="btn-list btn-list-print" id="fermerEtatSortieConsultBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Fermer
                </button>
            </div>
        </div>

        <div class="saisie-card" id="etatSortieListArea">
            <h1 class="sortie-print-title">ETAT SORTIE</h1>
            <div class="fournisseur-table-wrap">
                <table class="achats-commandes-table" id="etatsSortieTable">
                    <colgroup>
                        <col class="col-cmd-date">
                        <col class="col-cmd-bon">
                        <col class="col-cmd-code">
                        <col class="col-cmd-nom">
                        <col class="col-cmd-qte">
                        <col style="width:8%">
                        <col class="col-cmd-actions">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>N° Etat Production</th>
                            <th>Réf</th>
                            <th>Désignation</th>
                            <th>Qté</th>
                            <th>U</th>
                            <th class="col-actions no-print-sortie">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="etatsSortieTableBody">
                        <tr><td colspan="7" class="achats-commandes-empty">Aucun état de sortie</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="saisie-card hidden" id="etatSortieSaisieMode">
        <div class="saisie-card-header">
            <div>
                <h2 id="etatSortieFormTitle">Etat Sortie</h2>
                <span>Sélection depuis les états de production</span>
            </div>
        </div>
        <form class="saisie-form" id="etatSortieForm" novalidate>
            <div id="etatSortieFormArea">
                <div class="sortie-form-grid">
                    <div class="form-group">
                        <label for="es_date">Date</label>
                        <input type="date" id="es_date" class="form-input" readonly>
                    </div>
                    <div class="form-group">
                        <label for="es_numero_production">N° Etat production</label>
                        <select id="es_numero_production" class="form-input form-select">
                            <option value="">N° Etat production</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="es_ref">Réf</label>
                        <select id="es_ref" class="form-input form-select">
                            <option value="">Réf</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="es_designation">Désignation</label>
                        <select id="es_designation" class="form-input form-select">
                            <option value="">Désignation</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="es_quantite">Qté</label>
                        <input type="number" id="es_quantite" class="form-input" min="0.001" step="0.001" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label for="es_unite">U</label>
                        <input type="text" id="es_unite" class="form-input" readonly>
                    </div>
                </div>
            </div>
            <div class="form-actions sortie-form-actions">
                <button type="button" class="btn-form btn-form-primary" id="validerEtatSortieBtn">Valider</button>
                <button type="button" class="btn-form btn-form-secondary" id="fermerEtatSortieBtn">Fermer</button>
            </div>
        </form>
    </div>
</div>
