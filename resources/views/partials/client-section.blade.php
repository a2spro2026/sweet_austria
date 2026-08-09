{{-- Section Client : Fiche / Bon Vente / Réglement Vente / Balance — miroir Fournisseur/Achats --}}

{{-- Fiche Client --}}
<div id="ficheClientView" class="saisie-panel hidden">
    <div id="clientFormPanel" class="hidden">
        <div class="saisie-card">
            <div class="saisie-card-header">
                <div>
                    <h2 id="clientFormTitle">Fiche Client</h2>
                    <span id="clientFormSubtitle">Barre de saisie</span>
                </div>
            </div>
            <form class="saisie-form" id="ficheClientForm" novalidate>
                <div class="form-grid">
                    <div class="fr-inline-row fr-inline-row-idnom">
                        <div class="form-group">
                            <label for="cl_id">ID</label>
                            <input type="text" id="cl_id" name="id" class="form-input" readonly tabindex="-1">
                        </div>
                        <div class="form-group">
                            <label for="cl_nom">Nom Client</label>
                            <input type="text" id="cl_nom" name="nom" class="form-input" placeholder="Raison sociale ou nom" required>
                        </div>
                        <div class="form-group">
                            <label for="cl_type">Type</label>
                            <input type="text" id="cl_type" name="type" class="form-input" list="cl_type_list" placeholder="Type" autocomplete="off">
                            <datalist id="cl_type_list">
                                <option value="Rev">Rev — Revendeur</option>
                                <option value="Ste">Sté — Société</option>
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label for="cl_statut">Statut</label>
                            <input type="text" id="cl_statut" name="statut" class="form-input" list="cl_statut_list" placeholder="Statut" autocomplete="off">
                            <datalist id="cl_statut_list">
                                <option value="G/c">G/c — Grand compte</option>
                                <option value="Mc">Mc — Moyen compte</option>
                                <option value="Pc">Pc — Petit compte</option>
                            </datalist>
                        </div>
                    </div>
                    <div class="fr-inline-row fr-inline-row-adr">
                        <div class="form-group">
                            <label for="cl_adresse">Adresse</label>
                            <input type="text" id="cl_adresse" name="adresse" class="form-input" placeholder="Adresse complète">
                        </div>
                        <div class="form-group">
                            <label for="cl_telephone">Téléphone</label>
                            <input type="tel" id="cl_telephone" name="telephone" class="form-input" placeholder="06 XX XX XX XX">
                        </div>
                        <div class="form-group">
                            <label for="cl_fixe">Fixe</label>
                            <input type="tel" id="cl_fixe" name="fixe" class="form-input" placeholder="05 XX XX XX XX">
                        </div>
                        <div class="form-group">
                            <label for="cl_ville">Ville</label>
                            <input type="text" id="cl_ville" name="ville" class="form-input" list="cl_ville_list" placeholder="Ville" autocomplete="off">
                            <datalist id="cl_ville_list"></datalist>
                        </div>
                        <div class="form-group">
                            <label for="cl_email">E-mail</label>
                            <input type="email" id="cl_email" name="email" class="form-input" placeholder="contact@client.ma">
                        </div>
                    </div>
                    <div class="fr-inline-row">
                        <div class="form-group">
                            <label for="cl_type_paiement">Type Réglement</label>
                            <input type="text" id="cl_type_paiement" name="type_paiement" class="form-input" list="cl_type_paiement_list" placeholder="Type règlement" autocomplete="off">
                            <datalist id="cl_type_paiement_list">
                                <option value="Esp">Esp — Espèces</option>
                                <option value="Chq">Chq — Chèque</option>
                                <option value="Eff">Eff — Effet</option>
                                <option value="Vir">Vir — Virement</option>
                                <option value="Vers">Vers — Versement</option>
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label for="cl_solde">Solde initial (MAD)</label>
                            <input type="number" id="cl_solde" name="solde" class="form-input money-input" step="0.01" placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label for="cl_banque">Banque</label>
                            <input type="text" id="cl_banque" name="banque" class="form-input" placeholder="Nom de la banque">
                        </div>
                        <div class="form-group">
                            <label for="cl_rib">RIB</label>
                            <input type="text" id="cl_rib" name="rib" class="form-input" placeholder="RIB">
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-form btn-form-secondary" id="fermerClientForm">Fermer</button>
                    <button type="submit" class="btn-form btn-form-primary" id="validerClientBtn">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <div id="clientListPanel">
        <div class="list-toolbar no-print-client">
            <h2 class="list-toolbar-title">Fiche Client</h2>
            <div class="list-toolbar-actions">
                <button type="button" class="btn-list btn-list-add" id="ajouterClientBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Ajouter
                </button>
                <button type="button" class="btn-list btn-list-print" id="fermerClientListBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Fermer
                </button>
            </div>
        </div>
        <div id="clientPrintArea">
            <div class="fournisseur-table-wrap">
                <table class="fournisseur-table" id="clientsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Ville</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th>Solde</th>
                            <th class="col-actions no-print-client">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="clientsTableBody">
                        <tr><td colspan="8" class="fournisseur-empty">Aucun client enregistré</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Bon de Vente --}}
<div id="ventesView" class="saisie-panel hidden">
    <div id="ventesConsultMode">
        <div class="list-toolbar">
            <h2 class="list-toolbar-title">Bons de Vente</h2>
            <div class="list-toolbar-actions">
                <button type="button" class="btn-list btn-list-add" id="nouveauBonVentesBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Ajouter
                </button>
                <button type="button" class="btn-list btn-list-print" id="fermerVentesConsultBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Fermer
                </button>
            </div>
        </div>
        <div class="saisie-card" id="commandesPrintAreaVentes">
            <div class="fournisseur-table-wrap">
                <table class="achats-commandes-table" id="commandesListTableVentes">
                    <colgroup>
                        <col class="col-cmd-bon">
                        <col class="col-cmd-date">
                        <col class="col-cmd-code">
                        <col class="col-cmd-nom">
                        <col class="col-cmd-qte">
                        <col class="col-cmd-total">
                        <col class="col-cmd-reg">
                        <col class="col-cmd-actions">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Bon N°</th>
                            <th>Date</th>
                            <th>Code</th>
                            <th>Nom Client</th>
                            <th>Qté</th>
                            <th>Total</th>
                            <th>Réglement</th>
                            <th class="col-actions col-actions-cmd no-print-cmd">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="commandesListTableBodyVentes">
                        <tr><td colspan="8" class="achats-commandes-empty">Aucun bon de vente</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="saisie-card hidden" id="ventesSaisieMode">
        <div class="saisie-card-header">
            <div>
                <h2>Bon de Vente</h2>
                <span>Barre de saisie</span>
            </div>
        </div>
        <form class="saisie-form" id="ventesForm" novalidate>
            <div id="ventesPrintArea">
                <div class="achats-form-scroll">
                    <div class="ach-form-layout">
                        <input type="hidden" id="ve_code_client" value="">
                        <div class="ach-inline-row ach-row-head">
                            <div class="form-group">
                                <label for="ve_date_cmd">Date</label>
                                <input type="date" id="ve_date_cmd" class="form-input" readonly tabindex="-1">
                            </div>
                            <div class="form-group">
                                <label for="ve_bon">N° Bn</label>
                                <input type="text" id="ve_bon" class="form-input" placeholder="N° bon">
                            </div>
                            <div class="form-group">
                                <label for="ve_nom_client">Nom Client</label>
                                <input type="text" id="ve_nom_client" class="form-input" list="veClientNomsList" placeholder="Client" autocomplete="off">
                                <datalist id="veClientNomsList"></datalist>
                            </div>
                            <div class="form-group">
                                <label for="ve_type_reglement">Type régl</label>
                                <input type="text" id="ve_type_reglement" class="form-input" list="ve_type_reglement_list" placeholder="Type" autocomplete="off">
                                <datalist id="ve_type_reglement_list">
                                    <option value="Esp">Esp — Espèces</option>
                                    <option value="Chq">Chq — Chèque</option>
                                    <option value="Eff">Eff — Effet</option>
                                    <option value="Vir">Vir — Virement</option>
                                    <option value="Vers">Vers — Versement</option>
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label for="ve_echeance">Échéance</label>
                                <input type="date" id="ve_echeance" class="form-input">
                            </div>
                        </div>
                        <div class="ach-inline-row ach-row-art">
                            <div class="form-group">
                                <label for="ve_ligne_ref">Réf</label>
                                <input type="text" id="ve_ligne_ref" class="form-input" placeholder="Réf">
                            </div>
                            <div class="form-group">
                                <label for="ve_ligne_designation">Désignation</label>
                                <input type="text" id="ve_ligne_designation" class="form-input" list="ve_ligne_designation_list" placeholder="Désignation" autocomplete="off">
                                <datalist id="ve_ligne_designation_list"></datalist>
                            </div>
                            <div class="form-group">
                                <label for="ve_ligne_quantite">Qté</label>
                                <input type="number" id="ve_ligne_quantite" class="form-input" step="0.001" min="0" placeholder="0">
                            </div>
                            <div class="form-group">
                                <label for="ve_ligne_mesure">Unité</label>
                                <input type="text" id="ve_ligne_mesure" class="form-input" list="ve_ligne_mesure_list" placeholder="Unité" autocomplete="off">
                                <datalist id="ve_ligne_mesure_list"></datalist>
                            </div>
                            <div class="form-group">
                                <label for="ve_ligne_prix">Prix/U</label>
                                <input type="number" id="ve_ligne_prix" class="form-input money-input" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label for="ve_ligne_sous_total">Sous-Total</label>
                                <input type="text" id="ve_ligne_sous_total" class="form-input achats-sous-total-input readonly" readonly value="">
                            </div>
                            <div class="achats-add-article-wrap no-print-achats">
                                <button type="button" class="btn-add-article" id="ajouterLigneVentesBtn" title="Ajouter l'article" aria-label="Ajouter l'article">
                                    <svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="fournisseur-table-wrap">
                            <table class="fournisseur-table achats-lines-table" id="ventesLignesTable">
                                <thead>
                                    <tr>
                                        <th>Réf</th>
                                        <th>Désignation</th>
                                        <th>Qté</th>
                                        <th>Unité</th>
                                        <th>Prix/U</th>
                                        <th>Total</th>
                                        <th class="col-actions no-print-achats">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="ventesLignesTableBody">
                                    <tr><td colspan="7" class="fournisseur-empty">Aucun article ajouté</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="achats-articles-summary no-print-achats">
                            <div class="achats-total-bar" style="margin:0;padding:0;border:none;background:transparent;width:100%;justify-content:flex-end;">
                                <span>Total général</span>
                                <span id="ventesTotalGeneral">0,00 MAD</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions achats-doc-actions no-print-achats">
                <button type="button" class="btn-form btn-form-secondary" id="fermerVentesBtn">Fermer</button>
                <button type="button" class="btn-form btn-form-primary" id="enregistrerCommandeVentesBtn">Valider</button>
            </div>
        </form>
    </div>
</div>

{{-- Réglement Vente --}}
<div id="reglementVentesView" class="saisie-panel hidden">
    <div id="reglementVenteFormPanel" class="hidden">
        <div class="saisie-card">
            <div class="saisie-card-header">
                <div>
                    <h2 id="reglementVenteFormTitle">Réglement Vente</h2>
                    <span id="reglementVenteFormSubtitle">Barre de saisie</span>
                </div>
            </div>
            <form class="saisie-form" id="ficheReglementVenteForm" novalidate>
                <div class="rg-form-layout">
                    <div>
                        <div class="rg-inline-row rg-inline-row-1">
                            <div class="form-group">
                                <label for="rv_date">Date</label>
                                <input type="date" id="rv_date" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="rv_ref">N°</label>
                                <input type="text" id="rv_ref" class="form-input readonly" readonly tabindex="-1" placeholder="RégV0001">
                            </div>
                            <div class="form-group">
                                <label for="rv_client">Client</label>
                                <input type="text" id="rv_client" class="form-input" list="rv_client_list" placeholder="Nom client" autocomplete="off" required>
                                <datalist id="rv_client_list"></datalist>
                            </div>
                        </div>
                        <div class="rg-inline-row rg-inline-row-2">
                            <div class="form-group">
                                <label for="rv_type">Type Rég</label>
                                <input type="text" id="rv_type" class="form-input" list="rv_type_list" placeholder="Type" autocomplete="off" required>
                                <datalist id="rv_type_list">
                                    <option value="Esp">Esp — Espèces</option>
                                    <option value="Chq">Chq — Chèque</option>
                                    <option value="Eff">Eff — Effet</option>
                                    <option value="Vir">Vir — Virement</option>
                                    <option value="Vers">Vers — Versement</option>
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label for="rv_num">N° Rég</label>
                                <input type="text" id="rv_num" class="form-input" placeholder="N° règlement">
                            </div>
                            <div class="form-group">
                                <label for="rv_banque">Banque</label>
                                <input type="text" id="rv_banque" class="form-input" placeholder="Banque">
                            </div>
                            <div class="form-group">
                                <label for="rv_tire">Tiré</label>
                                <input type="text" id="rv_tire" class="form-input" placeholder="Nom du tiré">
                            </div>
                        </div>
                        <div class="rg-inline-row rg-inline-row-3">
                            <div class="form-group">
                                <label for="rv_montant_reg">Montant Rég</label>
                                <input type="number" id="rv_montant_reg" class="form-input money-input" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label for="rv_date_encaiss">Date Encaiss</label>
                                <input type="date" id="rv_date_encaiss" class="form-input">
                            </div>
                        </div>
                        <div id="rvBonsPanel" class="rg-bons-panel">
                            <div class="rg-bons-title">Bons de vente non soldés — choisissez le bon à régler</div>
                            <div class="fournisseur-table-wrap rg-bons-wrap">
                                <table class="fournisseur-table rg-bons-table" id="rvBonsTable">
                                    <thead>
                                        <tr>
                                            <th class="col-choose">Choisir</th>
                                            <th>Bon</th>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Déjà payé</th>
                                            <th>Solde</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rvBonsTableBody">
                                        <tr><td colspan="6" class="fournisseur-empty">Sélectionnez un client</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="rv_bon_num" value="">
                        <input type="hidden" id="rv_montant_bon" value="">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-form btn-form-secondary" id="fermerReglementVenteForm">Fermer</button>
                    <button type="submit" class="btn-form btn-form-primary" id="validerReglementVenteBtn">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <div id="reglementVenteConsultPanel">
        <div class="list-toolbar no-print-reglement">
            <h2 class="list-toolbar-title">Réglement Vente</h2>
            <div class="list-toolbar-actions">
                <button type="button" class="btn-list btn-list-add" id="ajouterReglementVenteBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Ajouter
                </button>
                <button type="button" class="btn-list btn-list-print" id="fermerReglementVenteConsultBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Fermer
                </button>
            </div>
        </div>
        <div class="kpi-grid rg-kpi-grid no-print-reglement">
            <div class="kpi-card blue">
                <div class="kpi-top">
                    <div class="kpi-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    <span class="kpi-badge kpi-badge-flat" id="rvKpiBadgeChq">0</span>
                </div>
                <div class="kpi-label">Total Chq</div>
                <div class="kpi-value" id="rvKpiTotalChq">0,00 MAD</div>
            </div>
            <div class="kpi-card orange">
                <div class="kpi-top">
                    <div class="kpi-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <span class="kpi-badge kpi-badge-flat" id="rvKpiBadgeEff">0</span>
                </div>
                <div class="kpi-label">Total Eff</div>
                <div class="kpi-value" id="rvKpiTotalEff">0,00 MAD</div>
            </div>
            <div class="kpi-card green">
                <div class="kpi-top">
                    <div class="kpi-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <span class="kpi-badge kpi-badge-flat" id="rvKpiBadgeEsp">0</span>
                </div>
                <div class="kpi-label">Total Esp</div>
                <div class="kpi-value" id="rvKpiTotalEsp">0,00 MAD</div>
            </div>
            <div class="kpi-card teal">
                <div class="kpi-top">
                    <div class="kpi-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M12 2v20"/><path d="M17 7l-5-5-5 5"/><path d="M7 17l5 5 5-5"/></svg>
                    </div>
                    <span class="kpi-badge kpi-badge-flat" id="rvKpiBadgeVir">0</span>
                </div>
                <div class="kpi-label">Total Vir</div>
                <div class="kpi-value" id="rvKpiTotalVir">0,00 MAD</div>
            </div>
        </div>
        <div id="reglementVentePrintArea">
            <div class="fournisseur-table-wrap">
                <table class="fournisseur-table" id="reglementsVenteTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>N°</th>
                            <th>Client</th>
                            <th>Bn°</th>
                            <th>Montant</th>
                            <th>Type Rég</th>
                            <th>N° Rég</th>
                            <th>Montant Rég</th>
                            <th class="col-actions no-print-reglement">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reglementsVenteTableBody">
                        <tr><td colspan="9" class="fournisseur-empty">Aucun réglement enregistré</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Balance Client --}}
<div id="balanceClientsView" class="saisie-panel hidden">
    <div id="balanceClientsConsultPanel">
        <div class="list-toolbar no-print-balance">
            <h2 class="list-toolbar-title">Balance Client</h2>
            <div class="list-toolbar-actions">
                <button type="button" class="btn-list btn-list-print" id="fermerBalanceClientsBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Fermer
                </button>
            </div>
        </div>
        <div id="balanceClientsPrintArea">
            <div class="fournisseur-table-wrap">
                <table class="fournisseur-table" id="balanceClientsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom Client</th>
                            <th>Montant</th>
                            <th>Montant Payé</th>
                            <th>Solde</th>
                            <th>Reliquat</th>
                        </tr>
                    </thead>
                    <tbody id="balanceClientsTableBody">
                        <tr><td colspan="6" class="fournisseur-empty">Aucune balance</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
