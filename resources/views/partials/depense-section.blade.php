<style>
    #etatDepenseView {
        flex: 1;
        min-height: 0;
        max-height: 100%;
        overflow: hidden;
    }
    #etatDepenseView:not(.hidden),
    #etatDepenseConsultMode:not(.hidden),
    #etatDepenseSaisieMode:not(.hidden) {
        display: flex;
        flex-direction: column;
    }
    #etatDepenseConsultMode,
    #etatDepenseListArea {
        flex: 1;
        min-height: 0;
        overflow: hidden;
    }
    #etatDepenseConsultMode .list-toolbar {
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
    #etatDepenseListArea {
        order: 1;
        display: flex;
        flex-direction: column;
        border-radius: 0 0 12px 12px;
    }
    #etatDepenseListArea .fournisseur-table-wrap {
        flex: 1;
        min-height: 0;
        overflow: auto;
    }
    #etatDepenseSaisieMode {
        max-height: calc(100vh - var(--header-h) - 96px);
    }
    #etatDepenseSaisieMode .saisie-form,
    #etatDepensePrintArea {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
    }
    #etatDepenseView .depense-document-header {
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        padding: 12px 18px;
        border-bottom: 2px solid #173f35;
    }
    #etatDepenseView .depense-document-header img {
        width: 55px;
        height: 55px;
        object-fit: contain;
    }
    #etatDepenseView .depense-document-header h2 {
        margin: 0;
        color: #173f35;
        font-size: 21px;
        font-weight: 900;
        letter-spacing: 1px;
    }
    #etatDepenseView .depense-form-scroll {
        flex: 1;
        min-height: 0;
        overflow: auto;
        padding: 14px 18px 8px;
    }
    #etatDepenseView .etat-saisie-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px 12px;
        align-items: end;
        width: 100%;
        margin-bottom: 12px;
    }
    #etatDepenseView .etat-saisie-grid .form-group {
        min-width: 0;
        margin: 0;
    }
    #etatDepenseView .etat-saisie-grid .span-2 {
        grid-column: span 2;
    }
    #etatDepenseView .etat-saisie-grid .form-input,
    #etatDepenseView .etat-saisie-grid .form-select {
        width: 100%;
        height: 38px;
        box-sizing: border-box;
    }
    #etatDepenseView .depense-entry-stack {
        display: contents;
    }
    #etatDepenseView .etat-saisie-add {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        margin: 0;
        min-width: 0;
    }
    #etatDepenseView .etat-saisie-add::before {
        content: '\00a0';
        font-size: 12px;
        line-height: 1;
        margin-bottom: 4px;
    }
    #etatDepenseView #ed_numero_production {
        cursor: pointer;
        padding-right: 25px;
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23004236' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat;
        background-position: right 7px center;
        background-size: 12px;
    }
    #etatDepenseView #ed_numero,
    #etatDepenseView #ed_sous_total {
        background: #edf5f1;
        font-weight: 700;
    }
    #etatDepenseView .etat-saisie-add .btn-add-article,
    #etatDepenseView .btn-add-article {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 10px;
        background: var(--green-dark, #003326);
        color: #fff;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 51, 38, 0.22);
        transition: background 0.15s, transform 0.15s, box-shadow 0.15s;
    }
    #etatDepenseView .btn-add-article:hover {
        background: #004d3a;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 51, 38, 0.28);
    }
    #etatDepenseView .btn-add-article svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
    }
    #etatDepenseView .depense-lines-wrap {
        min-height: 130px;
        max-height: 300px;
        overflow: auto;
        border: 1px solid var(--border);
        border-radius: 8px;
    }
    #etatDepenseView .depense-lines-wrap table {
        margin: 0;
        width: 100%;
    }
    #etatDepenseView .depense-form-actions {
        flex: 0 0 auto;
        margin: 0;
        padding: 12px 18px 16px;
        border-top: 1px solid var(--border);
        background: #fff;
        position: relative;
        z-index: 2;
    }
    @media (max-width: 980px) {
        #etatDepenseView .etat-saisie-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        #etatDepenseView .etat-saisie-grid .span-2 {
            grid-column: span 2;
        }
    }
    @media print {
        body.print-etat-depense .sidebar,
        body.print-etat-depense .sidebar-toggle,
        body.print-etat-depense .hero-header,
        body.print-etat-depense .page-footer {
            display: none !important;
        }
        body.print-etat-depense * { visibility: hidden !important; }
        body.print-etat-depense #etatDepensePrintArea,
        body.print-etat-depense #etatDepensePrintArea * { visibility: visible !important; }
        body.print-etat-depense #etatDepensePrintArea {
            position: absolute;
            inset: 0;
            width: 100%;
            max-height: none !important;
            overflow: visible !important;
            padding: 10mm;
            background: #fff;
        }
        body.print-etat-depense .depense-form-scroll,
        body.print-etat-depense .depense-lines-wrap {
            overflow: visible !important;
            max-height: none !important;
        }
        body.print-etat-depense .no-print-depense { display: none !important; }
    }
</style>

<div id="etatDepenseView" class="saisie-panel hidden">
    <div id="etatDepenseConsultMode">
        <div class="list-toolbar">
            <h2 class="list-toolbar-title">Etat Dépense</h2>
            <div class="list-toolbar-actions">
                <button type="button" class="btn-list btn-list-add" id="nouvelEtatDepenseBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Ajouter
                </button>
                <button type="button" class="btn-list btn-list-print" id="fermerEtatDepenseConsultBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Fermer
                </button>
            </div>
        </div>

        <div class="saisie-card" id="etatDepenseListArea">
            <div class="fournisseur-table-wrap">
                <table class="achats-commandes-table" id="etatsDepenseTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>N° E/D</th>
                            <th>N° E/P</th>
                            <th>Réf</th>
                            <th>Désignation</th>
                            <th>Quantité</th>
                            <th>U</th>
                            <th>Prix/U</th>
                            <th>Sous-Total</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="etatsDepenseTableBody">
                        <tr><td colspan="10" class="achats-commandes-empty">Aucun état dépense</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="saisie-card hidden" id="etatDepenseSaisieMode">
        <form class="saisie-form" id="etatDepenseForm" novalidate>
            <div id="etatDepensePrintArea">
                <div class="depense-document-header">
                    <img src="{{ asset('images/sweet-austria-logo.png') }}" alt="Logo Sweet Austria">
                    <h2>ETAT DÉPENSE</h2>
                </div>
                <div class="depense-form-scroll">
                    <div class="etat-saisie-grid">
                        <div class="form-group">
                            <label for="ed_date">Date</label>
                            <input type="date" id="ed_date" class="form-input" readonly>
                        </div>
                        <div class="form-group">
                            <label for="ed_numero">N° E/D</label>
                            <input type="text" id="ed_numero" class="form-input" readonly>
                        </div>
                        <div class="depense-entry-stack no-print-depense">
                            <div class="form-group">
                                <label for="ed_numero_production">N° E/P</label>
                                <select id="ed_numero_production" class="form-input form-select">
                                    <option value="">N° E/P</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ed_ref">Réf</label>
                                <input type="text" id="ed_ref" class="form-input" placeholder="Réf" autocomplete="off">
                            </div>
                            <div class="form-group span-2">
                                <label for="ed_designation">Désignation</label>
                                <input type="text" id="ed_designation" class="form-input" placeholder="Désignation" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="ed_quantite">Quantité</label>
                                <input type="number" id="ed_quantite" class="form-input" min="0.001" step="0.001" placeholder="0">
                            </div>
                            <div class="form-group">
                                <label for="ed_unite">U</label>
                                <input type="text" id="ed_unite" class="form-input" placeholder="U" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="ed_prix_unitaire">Prix/U</label>
                                <input type="number" id="ed_prix_unitaire" class="form-input" min="0" step="0.01" placeholder="0">
                            </div>
                            <div class="form-group span-2">
                                <label for="ed_sous_total">Sous-Total</label>
                                <input type="text" id="ed_sous_total" class="form-input" value="0" readonly>
                            </div>
                            <div class="etat-saisie-add">
                                <button type="button" class="btn-add-article" id="ajouterLigneDepenseBtn" title="Ajouter l'article" aria-label="Ajouter l'article">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="depense-lines-wrap">
                        <table class="fournisseur-table achats-lines-table">
                            <thead>
                                <tr>
                                    <th>N° E/P</th>
                                    <th>Réf</th>
                                    <th>Désignation</th>
                                    <th>Quantité</th>
                                    <th>U</th>
                                    <th>Prix/U</th>
                                    <th>Sous-Total</th>
                                    <th class="col-actions no-print-depense">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="etatDepenseLignesBody">
                                <tr><td colspan="8" class="fournisseur-empty">Aucune ligne ajoutée</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="form-actions depense-form-actions no-print-depense">
                <button type="button" class="btn-form btn-form-primary" id="validerEtatDepenseBtn">Valider</button>
                <button type="button" class="btn-form btn-form-secondary" id="imprimerEtatDepenseBtn">Imprimer</button>
                <button type="button" class="btn-form btn-form-secondary" id="fermerEtatDepenseBtn">Fermer</button>
            </div>
        </form>
    </div>
</div>
