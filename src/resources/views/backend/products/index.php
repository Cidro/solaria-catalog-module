<script>
    var contents = <?=json_encode([
        'products' => $products->toArray(),
        'categories' => $categories->toArray()
    ]);?>;
</script>
<div ng-cloak class="row" ng-controller="CatalogModuleProductsController" ng-init="init()">
    <div class="col-sm-8">
        <form class="form-filters">
            <fieldset>
                <legend>Filtros</legend>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="product-search">Búsqueda</label>
                        <input type="text" class="form-control" ng-model="filters.search">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="category-id">Categoría</label>
                        <ui-select ng-model="filters.category" theme="bootstrap" ng-change="filters.categories = filters.category.ids">
                            <ui-select-match placeholder="Seleccione una categoría">
                                <span ng-bind="$select.selected.name"></span>
                            </ui-select-match>
                            <ui-select-choices repeat="category in (categories | filter: $select.search)">
                                <span>{{ category.id }} - {{ getCategoryName(category) }}</span>
                            </ui-select-choices>
                        </ui-select>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <div class="col-sm-4">
        <a href="<?= url('backend/modules/catalog/products/create'); ?>" class="btn btn-primary">
            <span class="glyphicon glyphicon-plus"></span> Nuevo Producto
        </a>
    </div>
    <div class="col-sm-12" ng-cloak>
        <div class="text-center"><dir-pagination-controls></dir-pagination-controls></div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="1"><input type="checkbox" ng-model="filters.selectAllProducts"></th>
                    <th width="1">Id</th>
                    <th>Nombre</th>
                    <th width="1">Categoría</th>
                    <th width="1">Código</th>
                    <th width="1">Precio</th>
                    <th width="1">Orden</th>
                    <th width="1">Publicado</th>
                    <th width="1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="product in products | filter: filters.search | filter: categoryFilter | itemsPerPage: 25 track by $index ">
                    <td nowrap><input type="checkbox" ng-model="product.selected" ng-change="toggleSelected($index)" /></td>
                    <td nowrap>{{ product.id }}</td>
                    <td>{{ product.name }}</td>
                    <td nowrap>{{ product.category.name }}</td>
                    <td nowrap>{{ product.code }}</td>
                    <td nowrap>{{ product.price }}</td>
                    <td nowrap class="text-right">{{ product.ordering }}</td>
                    <td nowrap class="text-center"><span class="glyphicon" ng-class="product.published ? 'glyphicon-eye-open' : 'glyphicon-eye-close'"></span></td>
                    <td nowrap>
                        <a title="Editar" data-toggle="tooltip" href="{{ baseUrl }}backend/modules/catalog/products/edit/{{ product.id }}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-edit"></span></a>
                        <a title="Copiar" data-toggle="tooltip" href="{{ baseUrl }}backend/modules/catalog/products/copy/{{ product.id }}" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-copy"></span></a>
                        <a title="Eliminar" data-toggle="tooltip" ng-click="deleteProduct(product)" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>