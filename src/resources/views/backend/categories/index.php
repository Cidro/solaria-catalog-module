<script>
    var contents = <?=json_encode(['categories' => $categories->toArray()]);?>;
</script>
<div class="row" ng-controller="CatalogModuleCategoriesController" ng-init="init()">
    <div class="col-sm-8">
        <form class="form-filters">
            <fieldset>
                <legend>Filtros</legend>
                <div class="form-group">
                    <label for="category-search">Búsqueda</label>
                    <input type="text" class="form-control" ng-model="filters.search">
                </div>
            </fieldset>
        </form>
    </div>
    <div class="col-sm-4">
        <a href="<?= url('backend/modules/catalog/categories/create'); ?>" class="btn btn-primary">
            <span class="glyphicon glyphicon-plus"></span> Nueva Categoría
        </a>
    </div>
    <div class="col-sm-12" ng-cloak>
        <div class="text-center"><dir-pagination-controls></dir-pagination-controls></div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="1"><input type="checkbox" ng-model="filters.selectAllCategories"></th>
                    <th width="1">Id</th>
                    <th>Nombre</th>
                    <th width="1">Visible</th>
                    <th width="1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="category in categories | filter: filters.search | itemsPerPage: 25 track by $index ">
                    <td nowrap><input type="checkbox" ng-model="category.selected" ng-change="toggleSelected($index)" /></td>
                    <td nowrap>{{ category.id }}</td>
                    <td>{{ getCategoryName(category) }}</td>
                    <td nowrap><span class="glyphicon" ng-class="{'glyphicon-eye-open' : category.is_visible, 'glyphicon-eye-close' : !category.is_visible}"></span></td>
                    <td nowrap>
                        <a title="Editar" data-toggle="tooltip" href="{{ baseUrl }}backend/modules/catalog/categories/edit/{{ category.id }}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-edit"></span></a>
                        <a title="Copiar" data-toggle="tooltip" href="{{ baseUrl }}backend/modules/catalog/categories/copy/{{ category.id }}" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-copy"></span></a>
                        <a title="Eliminar" data-toggle="tooltip" class="btn btn-xs btn-danger" ng-click="deleteCategory(category)"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>