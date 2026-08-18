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
    #etatSortieSaisieMode .saisie-form,
    #etatSortiePrintArea {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
        padding: 0;
    }
    #etatSortieView .sortie-document-header {
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        padding: 12px 18px;
        border-bottom: 2px solid #173f35;
    }
    #etatSortieView .sortie-document-header img {
        width: 55px;
        height: 55px;
        object-fit: contain;
    }
    #etatSortieView .sortie-document-header h2 {
        margin: 0;
        color: #173f35;
        font-size: 21px;
        font-weight: 900;
        letter-spacing: 1px;
    }
    #etatSortieFormArea {
        flex: 1;
        min-height: 0;
        overflow: auto;
        padding: 18px;
    }
    #etatSortieView .etat-saisie-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px 12px;
        align-items: end;
        width: 100%;
    }
    #etatSortieView .etat-saisie-grid .form-group {
        min-width: 0;
        margin: 0;
    }
    #etatSortieView .etat-saisie-grid .span-2 {
        grid-column: span 2;
    }
    #etatSortieView .etat-saisie-grid .form-input,
    #etatSortieView .etat-saisie-grid .form-select {
        width: 100%;
        height: 38px;
        box-sizing: border-box;
    }
    #etatSortieView #es_numero,
    #etatSortieView #es_prix_vente {
        background: #edf5f1;
        font-weight: 700;
    }
    #etatSortieView #es_numero_production,
    #etatSortieView #es_numero_depense,
    #etatSortieView #es_categorie {
        cursor: pointer;
        padding-right: 22px;
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23004236' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat;
        background-position: right 6px center;
        background-size: 12px;
    }
    #etatSortieListArea .achats-commandes-table {
        table-layout: fixed;
    }
    #etatSortieListArea col.col-sortie-date { width: 9%; }
    #etatSortieListArea col.col-sortie-es { width: 9%; }
    #etatSortieListArea col.col-sortie-ep,
    #etatSortieListArea col.col-sortie-ed { width: 8%; }
    #etatSortieListArea col.col-sortie-ref { width: 8%; }
    #etatSortieListArea col.col-sortie-nom { width: 16%; }
    #etatSortieListArea col.col-sortie-cat { width: 10%; }
    #etatSortieListArea col.col-sortie-qte,
    #etatSortieListArea col.col-sortie-pv { width: 8%; }
    #etatSortieListArea col.col-sortie-u { width: 5%; }
    #etatSortieListArea col.col-sortie-actions { width: 11%; }
    #etatSortieView .sortie-form-actions {
        flex: 0 0 auto;
        margin: 0;
        padding: 12px 18px 16px;
        border-top: 1px solid var(--border);
        background: #fff;
    }
    @media (max-width: 900px) {
        #etatSortieView .etat-saisie-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        #etatSortieView .etat-saisie-grid .span-2 {
            grid-column: span 2;
        }
    }
    @media print {
        body.print-etat-sortie .sidebar,
        body.print-etat-sortie .sidebar-toggle,
        body.print-etat-sortie .hero-header,
        body.print-etat-sortie .page-footer {
            display: none !important;
        }
        body.print-etat-sortie * { visibility: hidden !important; }
        body.print-etat-sortie #etatSortiePrintArea,
        body.print-etat-sortie #etatSortiePrintArea * { visibility: visible !important; }
        body.print-etat-sortie #etatSortiePrintArea {
            position: absolute;
            inset: 0;
            width: 100%;
            max-height: none !important;
            overflow: visible !important;
            padding: 10mm;
            background: #fff;
        }
        body.print-etat-sortie #etatSortieFormArea {
            overflow: visible !important;
            max-height: none !important;
        }
        body.print-etat-sortie .no-print-sortie { display: none !important; }
        body.print-etat-sortie #etatSortieSaisieMode {
            display: block !important;
            max-height: none !important;
            box-shadow: none !important;
            border: 0 !important;
        }
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
                <button type="button" class="btn-list btn-list-print" id="fermerEtatSortieConsultBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Fermer
                </button>
            </div>
        </div>

        <div class="saisie-card" id="etatSortieListArea">
            <div class="fournisseur-table-wrap">
                <table class="achats-commandes-table" id="etatsSortieTable">
                    <colgroup>
                        <col class="col-sortie-date">
                        <col class="col-sortie-es">
                        <col class="col-sortie-ep">
                        <col class="col-sortie-ed">
                        <col class="col-sortie-ref">
                        <col class="col-sortie-nom">
                        <col class="col-sortie-cat">
                        <col class="col-sortie-qte">
                        <col class="col-sortie-u">
                        <col class="col-sortie-pv">
                        <col class="col-sortie-actions">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>N° E/S</th>
                            <th>N° E/P</th>
                            <th>N° E/D</th>
                            <th>Réf</th>
                            <th>Désignation</th>
                            <th>Catégorie</th>
                            <th>Quantité</th>
                            <th>U</th>
                            <th>P/V</th>
                            <th class="col-actions no-print-sortie">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="etatsSortieTableBody">
                        <tr><td colspan="11" class="achats-commandes-empty">Aucun état de sortie</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="saisie-card hidden" id="etatSortieSaisieMode">
        <form class="saisie-form" id="etatSortieForm" novalidate>
            <div id="etatSortiePrintArea">
                <div class="sortie-document-header">
                    <img src="{{ asset('images/sweet-austria-logo.png') }}" alt="Logo Sweet Austria">
                    <h2>ETAT SORTIE</h2>
                </div>
                <div id="etatSortieFormArea">
                    <div class="etat-saisie-grid">
                        <div class="form-group">
                            <label for="es_date">Date</label>
                            <input type="date" id="es_date" class="form-input" readonly>
                        </div>
                        <div class="form-group">
                            <label for="es_numero">N° E/S</label>
                            <input type="text" id="es_numero" class="form-input" readonly>
                        </div>
                        <div class="form-group">
                            <label for="es_numero_production">N° E/P</label>
                            <select id="es_numero_production" class="form-input form-select">
                                <option value="">N° E/P</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="es_numero_depense">N° E/D</label>
                            <select id="es_numero_depense" class="form-input form-select">
                                <option value="">N° E/D</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="es_ref">Réf</label>
                            <input type="text" id="es_ref" class="form-input" placeholder="Réf" autocomplete="off">
                        </div>
                        <div class="form-group span-2">
                            <label for="es_designation">Désignation</label>
                            <input type="text" id="es_designation" class="form-input" placeholder="Désignation" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="es_categorie">Catégorie</label>
                            <select id="es_categorie" class="form-input form-select">
                                <option value="">Catégorie</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="es_quantite">Quantité</label>
                            <input type="number" id="es_quantite" class="form-input" min="0.001" step="0.001" placeholder="0">
                        </div>
                        <div class="form-group">
                            <label for="es_unite">U</label>
                            <input type="text" id="es_unite" class="form-input" placeholder="U" autocomplete="off">
                        </div>
                        <div class="form-group span-2">
                            <label for="es_prix_vente">P/V</label>
                            <input type="number" id="es_prix_vente" class="form-input" min="0" step="0.01" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions sortie-form-actions no-print-sortie">
                <button type="button" class="btn-form btn-form-primary" id="validerEtatSortieBtn">Valider</button>
                <button type="button" class="btn-form btn-form-secondary" id="imprimerEtatSortieBtn">Imprimer</button>
                <button type="button" class="btn-form btn-form-secondary" id="fermerEtatSortieBtn">Fermer</button>
            </div>
        </form>
    </div>
</div>
