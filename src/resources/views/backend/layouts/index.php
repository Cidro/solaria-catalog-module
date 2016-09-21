<script>
    var contents = <?=json_encode(['layouts' => $layouts->toArray()]);?>;
</script>
<div ng-cloak class="row" ng-controller="CatalogModuleLayoutsController" ng-init="init()">
    <div class="col-sm-8">
        <form class="form-filters">
            <fieldset>
                <legend>Filtros</legend>
                <div class="form-group">
                    <label for="layout-search">Búsqueda</label>
                    <input type="text" class="form-control" ng-model="filters.search">
                </div>
            </fieldset>
        </form>
    </div>
    <div class="col-sm-4">
        <a href="<?= url('backend/modules/catalog/layouts/create'); ?>" class="btn btn-primary">
            <span class="glyphicon glyphicon-plus"></span> Nueva Plantilla
        </a>
    </div>
    <div class="col-sm-12">
        <div class="text-center"><dir-pagination-controls></dir-pagination-controls></div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="1"><input type="checkbox" ng-model="filters.selectAllLayouts"></th>
                    <th width="1">Id</th>
                    <th>Nombre</th>
                    <th>Alias</th>
                    <th width="1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="layout in layouts | filter: filters.search | itemsPerPage: 25 track by $index ">
                    <td nowrap><input type="checkbox" ng-model="layout.selected" ng-change="toggleSelected($index)" /></td>
                    <td>{{ layout.id }}</td>
                    <td nowrap>
                        {{ layout.name }}
                        <span title="Principal" data-toggle="tooltip" ng-if="layout.default" class="text-success glyphicon glyphicon-check"></span>
                    </td>
                    <td>{{ layout.alias }}</td>
                    <td nowrap>
                        <a title="Editar" data-toggle="tooltip" href="{{ baseUrl }}backend/modules/catalog/layouts/edit/{{ layout.id }}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-edit"></span></a>
                        <a title="Eliminar" data-toggle="tooltip" ng-click="deleteLayout(layout)" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>