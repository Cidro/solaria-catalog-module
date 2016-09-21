<script>
    var contents = <?=json_encode(['currencies' => $currencies->toArray()]);?>;
</script>
<div ng-cloak class="row" ng-controller="CatalogModuleCurrenciesController" ng-init="init()">
    <div class="col-sm-8">
        <form class="form-filters">
            <fieldset>
                <legend>Filtros</legend>
                <div class="form-group">
                    <label for="currency-search">Búsqueda</label>
                    <input type="text" class="form-control" ng-model="filters.search">
                </div>
            </fieldset>
        </form>
    </div>
    <div class="col-sm-4">
        <a href="<?= url('backend/modules/catalog/currencies/create'); ?>" class="btn btn-primary">
            <span class="glyphicon glyphicon-plus"></span> Nueva Moneda
        </a>
    </div>
    <div class="col-sm-12">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="1"><input type="checkbox" ng-model="filters.selectAllProducts"></th>
                    <th width="1">Id</th>
                    <th>Nombre</th>
                    <th width="1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="currency in currencies | filter: filters.search | itemsPerPage: 25 track by $index ">
                    <td nowrap><input type="checkbox" ng-model="currency.selected" ng-change="toggleSelected($index)" /></td>
                    <td>{{ currency.id }}</td>
                    <td nowrap>
                        {{ currency.name }}
                        <span title="Principal" data-toggle="tooltip" ng-if="currency.default" class="text-success glyphicon glyphicon-check"></span>
                    </td>
                    <td nowrap>
                        <a title="Editar" data-toggle="tooltip" href="{{ baseUrl }}backend/modules/catalog/currencies/edit/{{ currency.id }}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-edit"></span></a>
                        <a title="Eliminar" data-toggle="tooltip" ng-click="deleteCurrency(currency)" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>