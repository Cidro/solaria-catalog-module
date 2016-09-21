<script>
    var contents = <?=json_encode([
        'taxes' => $taxes->toArray()
    ]);?>;
</script>
<div ng-cloak class="row" ng-controller="CatalogModuleTaxesController" ng-init="init()">
    <div class="col-sm-8">
        <form class="form-filters">
            <fieldset>
                <legend>Filtros</legend>
                <div class="form-group">
                    <label for="tax-search">Búsqueda</label>
                    <input type="text" class="form-control" ng-model="filters.search">
                </div>
            </fieldset>
        </form>
    </div>
    <div class="col-sm-4">
        <a href="<?= url('backend/modules/catalog/taxes/create'); ?>" class="btn btn-primary">
            <span class="glyphicon glyphicon-plus"></span> Nuevo Impuesto
        </a>
    </div>
    <div class="col-sm-12">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="1"><input type="checkbox" ng-model="filters.selectAllTaxes"></th>
                    <th width="1">Id</th>
                    <th>Nombre</th>
                    <th width="1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="tax in taxes | filter: filters.search | itemsPerPage: 25 track by $index ">
                    <td nowrap><input type="checkbox" ng-model="tax.selected" ng-change="toggleSelected($index)" /></td>
                    <td>{{ tax.id }}</td>
                    <td nowrap>
                        {{ tax.name }}
                        <span title="Principal" data-toggle="tooltip" ng-if="tax.default" class="text-success glyphicon glyphicon-check"></span>
                    </td>
                    <td nowrap>
                        <a title="Editar" data-toggle="tooltip" href="{{ baseUrl }}backend/modules/catalog/taxes/edit/{{ tax.id }}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>
                        <a title="Eliminar" data-toggle="tooltip" ng-click="deleteTax(tax)" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>