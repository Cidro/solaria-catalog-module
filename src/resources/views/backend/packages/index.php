<script>
    var contents = <?=json_encode(['packages' => $packages->toArray()]);?>;
</script>
<div ng-cloak class="row" ng-controller="CatalogModulePackagesController" ng-init="init()">
    <div class="col-sm-8">
        <form class="form-filters">
            <fieldset>
                <legend>Filtros</legend>
                <div class="form-group">
                    <label for="package-search">Búsqueda</label>
                    <input type="text" class="form-control" ng-model="filters.search">
                </div>
            </fieldset>
        </form>
    </div>
    <div class="col-sm-4">
        <a href="<?= url('backend/modules/catalog/packages/create'); ?>" class="btn btn-primary">
            <span class="glyphicon glyphicon-plus"></span> Nuevo Paquete
        </a>
    </div>
    <div class="col-sm-12">
        <div class="text-center"><dir-pagination-controls></dir-pagination-controls></div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="1"><input type="checkbox" ng-model="filters.selectAllProducts"></th>
                    <th width="1">Id</th>
                    <th>Nombre</th>
                    <th width="1">Precio</th>
                    <th width="1">Orden</th>
                    <th width="1">Publicado</th>
                    <th width="1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="package in packages | filter: filters.search | itemsPerPage: 25 track by $index ">
                    <td nowrap><input type="checkbox" ng-model="package.selected" ng-change="toggleSelected($index)" /></td>
                    <td nowrap>{{ package.id }}</td>
                    <td>{{ package.name }}</td>
                    <td>{{ package.price }}</td>
                    <td nowrap class="text-right">{{ package.ordering }}</td>
                    <td nowrap class="text-center"><span class="glyphicon" ng-class="package.published ? 'glyphicon-eye-open' : 'glyphicon-eye-close'"></span></td>
                    <td nowrap>
                        <a title="Editar" data-toggle="tooltip" href="{{ baseUrl }}backend/modules/catalog/packages/edit/{{ package.id }}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-edit"></span></a>
                        <a title="Eliminar" data-toggle="tooltip" ng-click="deletePackage(package)" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>