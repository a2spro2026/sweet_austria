<style>
    #etatProductionView .production-document-header {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        padding: 14px 18px 12px;
        border-bottom: 2px solid #173f35;
    }
    #etatProductionView .production-document-logo {
        width: 58px;
        height: 58px;
        object-fit: contain;
    }
    #etatProductionView .production-document-title {
        margin: 0;
        color: #173f35;
        font-size: 22px;
        font-weight: 900;
        letter-spacing: 1.5px;
    }
    #etatProductionView .etat-saisie-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px 12px;
        align-items: end;
        width: 100%;
        margin-bottom: 12px;
    }
    #etatProductionView .etat-saisie-grid .form-group {
        min-width: 0;
        margin: 0;
    }
    #etatProductionView .etat-saisie-grid .span-2 {
        grid-column: span 2;
    }
    #etatProductionView .etat-saisie-grid .form-input,
    #etatProductionView .etat-saisie-grid .form-select {
        width: 100%;
        height: 38px;
        box-sizing: border-box;
    }
    #etatProductionView .production-entry-stack {
        display: contents;
    }
    #etatProductionView .etat-saisie-add {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        margin: 0;
        min-width: 0;
    }
    #etatProductionView .etat-saisie-add::before {
        content: '\00a0';
        font-size: 12px;
        line-height: 1;
        margin-bottom: 4px;
    }
    #etatProductionView .etat-saisie-add .btn-add-article {
        width: 38px;
        height: 38px;
    }
    #etatProductionView #ep_ref,
    #etatProductionView #ep_designation {
        cursor: pointer;
        padding-right: 26px;
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23004236' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 13px 13px;
    }
    #etatProductionView .production-stock,
    #etatProductionView #ep_unite {
        background: #edf5f1;
        font-weight: 800;
        color: #173f35;
    }
    #etatProductionView .production-actions {
        display: flex;
        justify-content: center;
        gap: 5px;
        white-space: nowrap;
    }
    #etatProductionView .production-actions button {
        width: 31px;
        height: 31px;
        border: 0;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        cursor: pointer;
    }
    #etatProductionView .production-actions svg { width: 16px; height: 16px; }
    #etatProductionView .prod-action-view { background: #2563eb; }
    #etatProductionView .prod-action-edit { background: #d97706; }
    #etatProductionView .prod-action-delete { background: #dc2626; }
    #etatProductionView .prod-action-print { background: #177245; }
    #etatProductionView #modifierEtatProductionBtn,
    #etatProductionView #imprimerEtatProductionBtn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        flex: 0 0 auto;
    }
    #etatProductionView #modifierEtatProductionBtn svg,
    #etatProductionView #imprimerEtatProductionBtn svg {
        width: 18px;
        height: 18px;
    }
    #etatProductionView .production-list-print-title {
        display: none;
    }
    @media (max-width: 980px) {
        #etatProductionView .etat-saisie-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        #etatProductionView .etat-saisie-grid .span-2 { grid-column: span 2; }
    }
    @media print {
        body.print-etat-production * { visibility: hidden !important; }
        body.print-etat-production #etatProductionPrintArea,
        body.print-etat-production #etatProductionPrintArea * { visibility: visible !important; }
        body.print-etat-production #etatProductionPrintArea {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10mm;
            background: #fff;
            display: block !important;
            overflow: visible !important;
            max-height: none !important;
        }
        body.print-etat-production #etatProductionPrintArea .achats-form-scroll,
        body.print-etat-production #etatProductionPrintArea .ach-form-layout,
        body.print-etat-production #etatProductionPrintArea .fournisseur-table-wrap {
            display: block !important;
            overflow: visible !important;
            max-height: none !important;
            border: 0 !important;
        }
        body.print-etat-production .no-print-production { display: none !important; }
        body.print-etat-production #etatProductionSaisieMode {
            display: block !important;
            max-height: none !important;
            box-shadow: none !important;
            border: 0 !important;
        }
        body.print-etats-production-list * { visibility: hidden !important; }
        body.print-etats-production-list #etatProductionListArea,
        body.print-etats-production-list #etatProductionListArea * { visibility: visible !important; }
        body.print-etats-production-list #etatProductionListArea {
            position: absolute;
            inset: 0;
            width: 100%;
            max-height: none !important;
            overflow: visible !important;
            border: 0 !important;
            box-shadow: none !important;
            padding: 10mm;
            background: #fff;
        }
        body.print-etats-production-list #etatProductionListArea .production-list-print-title {
            display: block !important;
            visibility: visible !important;
            margin: 0 0 18px;
            text-align: center;
            color: #173f35;
            font-size: 24px;
        }
        body.print-etats-production-list #etatProductionListArea .fournisseur-table-wrap {
            overflow: visible !important;
        }
        body.print-etats-production-list .col-actions { display: none !important; }
    }
</style>

<div id="etatProductionView" class="saisie-panel hidden">
    <div id="etatProductionConsultMode">
        <div class="list-toolbar">
            <h2 class="list-toolbar-title">Etat Production</h2>
            <div class="list-toolbar-actions">
                <button type="button" class="btn-list btn-list-add" id="nouvelEtatProductionBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Ajouter
                </button>
                <button type="button" class="btn-list btn-list-print" id="imprimerEtatsProductionBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Imprimer
                </button>
                <button type="button" class="btn-list btn-list-print" id="fermerEtatProductionConsultBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Fermer
                </button>
            </div>
        </div>
        <div class="saisie-card" id="etatProductionListArea">
            <h1 class="production-list-print-title">ETAT PRODUCTION</h1>
            <div class="fournisseur-table-wrap">
                <table class="achats-commandes-table">
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
                            <th>N° E/P</th>
                            <th>Réf</th>
                            <th>Désignation</th>
                            <th>Quantité</th>
                            <th>U</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="etatsProductionTableBody">
                        <tr><td colspan="7" class="achats-commandes-empty">Aucun état de production</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="saisie-card hidden" id="etatProductionSaisieMode">
        <form class="saisie-form" id="etatProductionForm" novalidate>
            <div id="etatProductionPrintArea">
                <div class="production-document-header">
                    <img src="{{ asset('images/sweet-austria-logo.png') }}" alt="Logo Sweet Austria" class="production-document-logo">
                    <h2 class="production-document-title">ETAT PRODUCTION</h2>
                </div>
                <div class="achats-form-scroll">
                    <div class="ach-form-layout">
                        <div class="etat-saisie-grid">
                            <div class="form-group">
                                <label for="ep_date">Date</label>
                                <input type="date" id="ep_date" class="form-input" readonly>
                            </div>
                            <div class="form-group">
                                <label for="ep_numero">N° E/P</label>
                                <input type="text" id="ep_numero" class="form-input" readonly>
                            </div>
                            <div class="production-entry-stack no-print-production">
                                <div class="form-group">
                                    <label for="ep_ref">Réf</label>
                                    <select id="ep_ref" class="form-input form-select">
                                        <option value="">Réf</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ep_designation">Désignation</label>
                                    <select id="ep_designation" class="form-input form-select">
                                        <option value="">Désignation</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ep_stock">Stock</label>
                                    <input type="text" id="ep_stock" class="form-input production-stock" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="ep_quantite">Quantité</label>
                                    <input type="number" id="ep_quantite" class="form-input" min="0.001" step="0.001" placeholder="0">
                                </div>
                                <div class="form-group">
                                    <label for="ep_unite">U</label>
                                    <input type="text" id="ep_unite" class="form-input" readonly>
                                </div>
                                <div class="etat-saisie-add">
                                    <button type="button" class="btn-add-article" id="ajouterLigneProductionBtn" title="Ajouter le produit" aria-label="Ajouter le produit">
                                        <svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="fournisseur-table-wrap">
                            <table class="fournisseur-table achats-lines-table">
                                <thead>
                                    <tr>
                                        <th>Réf</th>
                                        <th>Désignation</th>
                                        <th>Stock</th>
                                        <th>Quantité</th>
                                        <th>U</th>
                                        <th class="col-actions no-print-production">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="etatProductionLignesBody">
                                    <tr><td colspan="6" class="fournisseur-empty">Aucun produit ajouté</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions achats-doc-actions no-print-production">
                <button type="button" class="btn-icon-row btn-icon-edit hidden" id="modifierEtatProductionBtn" title="Modifier" aria-label="Modifier">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button type="button" class="btn-icon-row btn-icon-print" id="imprimerEtatProductionBtn" title="Imprimer" aria-label="Imprimer">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                </button>
                <button type="button" class="btn-form btn-form-primary" id="validerEtatProductionBtn">Valider</button>
                <button type="button" class="btn-form btn-form-secondary" id="fermerEtatProductionBtn">Fermer</button>
            </div>
        </form>
    </div>
</div>
