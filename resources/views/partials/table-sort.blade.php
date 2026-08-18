<style>
    table.fournisseur-table thead th.th-sortable,
    table.achats-commandes-table thead th.th-sortable,
    table.produits-table thead th.th-sortable,
    table.stock-table thead th.th-sortable,
    table.materiels-table thead th.th-sortable,
    table.rg-bons-table thead th.th-sortable {
        cursor: pointer;
        user-select: none;
        white-space: nowrap;
    }
    .th-sort-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        max-width: 100%;
    }
    .th-sort-arrows {
        display: inline-flex;
        flex-direction: column;
        justify-content: center;
        gap: 1px;
        flex-shrink: 0;
        line-height: 0;
        opacity: 0.38;
        transform: translateY(-0.5px);
    }
    .th-sort-arrows span {
        display: block;
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
    }
    .th-sort-arrows .arr-up {
        border-bottom: 5px solid currentColor;
    }
    .th-sort-arrows .arr-down {
        border-top: 5px solid currentColor;
    }
    thead th.th-sortable:hover .th-sort-arrows {
        opacity: 0.7;
    }
    thead th.th-sortable.is-asc .th-sort-arrows,
    thead th.th-sortable.is-desc .th-sort-arrows {
        opacity: 1;
        color: var(--green-dark, #003326);
    }
    thead th.th-sortable.is-asc .arr-down,
    thead th.th-sortable.is-desc .arr-up {
        opacity: 0.28;
    }
    @media print {
        .th-sort-arrows { display: none !important; }
        thead th.th-sortable { cursor: default; }
    }
</style>
