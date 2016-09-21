<script>
    var contents = <?=json_encode(['locations' => $locations->toArray()]);?>;
</script>
<div ng-cloak class="row" ng-controller="CatalogModuleLocationsController" ng-init="init()">
    <div class="col-sm-8">
        <form class="form-filters">
            <fieldset>
                <legend>Filtros</legend>
                <div class="form-group">
                    <label for="location-search">Búsqueda</label>
                    <input type="text" class="form-control" ng-model="filters.search">
                </div>
            </fieldset>
        </form>
    </div>
    <div class="col-sm-4">
        <a href="<?= url('backend/modules/catalog/locations/create'); ?>" class="btn btn-primary">
            <span class="glyphicon glyphicon-plus"></span> Nueva Localidad
        </a>
    </div>
    <div class="col-sm-12">
        <div class="text-center"><dir-pagination-controls></dir-pagination-controls></div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="1"><input type="checkbox" ng-model="filters.selectAllLocations"></th>
                    <th width="1">Id</th>
                    <th>Nombre</th>
                    <th width="1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="location in locations | filter: filters.search | itemsPerPage: 25 track by $index ">
                    <td nowrap><input type="checkbox" ng-model="location.selected" ng-change="toggleSelected($index)" /></td>
                    <td nowrap>{{ location.id }}</td>
                    <td>{{ getLocationName(location) }}</td>
                    <td nowrap>
                        <a title="Editar" data-toggle="tooltip" href="{{ baseUrl }}backend/modules/catalog/locations/edit/{{ location.id }}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-edit"></span></a>
                        <a title="Eliminar" data-toggle="tooltip" ng-click="deleteLocation(location)" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>