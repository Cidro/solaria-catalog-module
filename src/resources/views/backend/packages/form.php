<script>
    var contents = <?=json_encode([
        'package' => $package->toArray(),
        'products' => $products->toArray(),
        'categories' => $categories->toArray(),
        'packages' => $packages->toArray(),
        'languages' => $languages->toArray()
    ]);?>;
</script>
<div ng-controller="CatalogModulePackageFormController" ng-init="init()" ng-cloak>
    <form ng-submit="submit()">
        <div>
            <ul class="nav nav-tabs">
                <li ng-class="{active: activeTab == 'general'}">
                    <a href="#" ng-click="setActiveTab('general')">
                        <span class="glyphicon glyphicon-list-alt"></span> General
                    </a>
                </li>
                <li ng-class="{active: activeTab == 'images'}">
                    <a href="#" ng-click="setActiveTab('images')">
                        <span class="glyphicon glyphicon-list-alt"></span> Imágenes
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" ng-class="{active: activeTab == 'general'}">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <languageable languages="languages" languageable-source="{ data: package.data }">
                                    <input type="text" id="name" class="form-control" ng-model="data.name">
                                </languageable>
                            </div>
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <languageable languages="languages" languageable-source="{ data: package.data, tinymceOptions: tinymceOptions }">
                                    <textarea ui-tinymce="tinymceOptions" class="form-control" ng-model="data.description"></textarea>
                                </languageable>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="ordering">Ordenamiento</label>
                                    <input type="text" id="ordering" class="form-control" ng-model="package.ordering">
                                </div>
                                <div class="checkbox col-sm-6">
                                    <label for="published">
                                        <input type="checkbox" id="published" ng-model="package.published">
                                        Publicado
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" id="code" class="form-control" ng-model="package.code">
                            </div>
                            <div class="form-group">
                                <label for="price">Precio</label>
                                <input type="text" id="price" class="form-control" ng-model="package.price">
                            </div>
                            <div class="form-group">
                                <label for="description">Categoría</label>
                                <div class="input-group">
                                    <ui-select ng-model="package.category_id" theme="bootstrap" ng-change="updateProductAttributes()">
                                        <ui-select-match placeholder="Seleccione una categoría">
                                            <span ng-bind="$select.selected.name"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="category.id as category in (categories | filter: $select.search) track by category.id">
                                            <span>{{ category.id }} - {{ category.name }}</span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <span class="input-group-btn">
                                      <button type="button" ng-click="package.category_id = null" class="btn btn-default">
                                          <span class="glyphicon glyphicon-trash"></span>
                                      </button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="layout">Sub Paquetes</label>
                                <div class="form-group">
                                    <ui-select multiple ng-model="package.childPackagesIds" theme="bootstrap" ng-disabled="disabled">
                                        <ui-select-match placeholder="Seleccione los paquetes">{{$item.name}}</ui-select-match>
                                        <ui-select-choices repeat="package.id as package in (packages | filter: $select.search)">
                                            <div ng-bind-html="package.name | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="layout">Productos</label>
                                <div class="form-group">
                                    <ui-select multiple ng-model="package.productsIds" theme="bootstrap" ng-disabled="disabled">
                                        <ui-select-match placeholder="Seleccione los produtos">{{$item.name}}</ui-select-match>
                                        <ui-select-choices repeat="product.id as product in (products | filter: $select.search)">
                                            <div ng-bind-html="product.name | highlight: $select.search"></div>
                                            <strong ng-bind-html="product.category.name"></strong>
                                        </ui-select-choices>
                                    </ui-select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" ng-class="{active: activeTab == 'images'}">
                    <div class="row">
                        <div class="col-sm-4" ng-repeat="image in package.images track by $index">
                            <div class="well">
                                <button type="button" class="pull-right btn btn-danger btn-xs" ng-click="removeImage($index)">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="radio" ng-model="image.default" ng-value="true" ng-change="changeMainImage($index)">
                                        Principal
                                    </label>
                                </div>
                                <layout-field-image field="image" ng-model="image.config" index="$index"></layout-field-image>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="well text-center">
                                <button type="button" ng-click="addImage()" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="pull-right btn btn-primary">Guardar</button>
    </form>
</div>