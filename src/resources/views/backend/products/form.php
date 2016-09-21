<script>
    var contents = <?=json_encode([
        'product' => $product->toArray(),
        'products' => $products->toArray(),
        'locations' => $locations->toArray(),
        'languages' => $languages->toArray(),
        'categories' => $categories->toArray(),
        'attributes' => $attributes->toArray(),
        'pages' => $pages->toArray(),
        'packages' => $packages->toArray(),
        'isCopy' => $isCopy
    ]);?>;
</script>
<div ng-controller="CatalogModuleProductFormController" ng-init="init()" ng-cloak>
    <form ng-submit="submit()">
        <div>
            <ul class="nav nav-tabs">
                <li ng-class="{active: activeTab == 'general'}">
                    <a href="#" ng-click="setActiveTab('general')">
                        <span class="glyphicon glyphicon-list-alt"></span> General
                    </a>
                </li>
                <li ng-class="{active: activeTab == 'attributes'}">
                    <a href="#" ng-click="setActiveTab('attributes')">
                        <span class="glyphicon glyphicon-list-alt"></span> Atributos
                    </a>
                </li>
                <li ng-class="{active: activeTab == 'locations'}">
                    <a href="#" ng-click="setActiveTab('locations')">
                        <span class="glyphicon glyphicon-list-alt"></span> Localidades
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
                                <languageable languages="languages" languageable-source="{ data: product.data }">
                                    <input type="text" id="name" class="form-control" ng-model="data.name">
                                </languageable>
                            </div>
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <languageable languages="languages" languageable-source="{ data: product.data, tinymceOptions: tinymceOptions }">
                                    <textarea ui-tinymce="tinymceOptions" class="form-control" ng-model="data.description"></textarea>
                                </languageable>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="ordering">Ordenamiento</label>
                                    <input type="text" id="ordering" class="form-control" ng-model="product.ordering">
                                </div>
                                <div class="checkbox col-sm-6">
                                    <label for="published">
                                        <input type="checkbox" id="published" ng-model="product.published">
                                        Publicado
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="code">Código</label>
                                    <input type="text" id="code" class="form-control" ng-model="product.code">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="sku">Sku</label>
                                    <input type="text" id="sku" class="form-control" ng-model="product.sku">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="price">Precio</label>
                                    <input type="text" id="price" class="form-control" ng-model="product.price">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="leasing_price">Precio Arriendo</label>
                                    <input type="text" id="leasing_price" class="form-control" ng-model="product.leasing_price">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="category">Categoría</label>
                                <div class="input-group">
                                    <ui-select ng-model="product.category_id" theme="bootstrap" ng-change="updateProductAttributes()">
                                        <ui-select-match placeholder="Seleccione una categoría">
                                            <span ng-bind="$select.selected.name"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="category.id as category in (categories | filter: $select.search) track by category.id">
                                            <span>{{ category.id }} - {{ category.name }}</span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <span class="input-group-btn">
                                      <button type="button" ng-click="product.category_id = null" class="btn btn-default">
                                          <span class="glyphicon glyphicon-trash"></span>
                                      </button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="parent_id">Producto padre</label>
                                <div class="input-group">
                                    <ui-select allow-clear ng-model="product.parent_id" theme="bootstrap" ng-change="updateProductAttributes()">
                                        <ui-select-match placeholder="Seleccione un producto">
                                            <span ng-bind="$select.selected.name"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="product.id as product in (products | filter: $select.search) track by product.id">
                                            <span>{{ product.id }} - {{ product.name }}</span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <span class="input-group-btn">
                                      <button type="button" ng-click="product.parent_id = null" class="btn btn-default">
                                          <span class="glyphicon glyphicon-trash"></span>
                                      </button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="page">Página</label>
                                <div class="input-group">
                                    <ui-select allow-clear ng-model="product.page_id" theme="bootstrap">
                                        <ui-select-match placeholder="Seleccione una página">
                                            <span ng-bind="$select.selected.title"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="page.id as page in (pages | filter: $select.search) track by page.id">
                                            <span>{{ page.id }} - {{ page.title }}</span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <span class="input-group-btn">
                                      <button type="button" ng-click="product.page_id = null" class="btn btn-default">
                                          <span class="glyphicon glyphicon-trash"></span>
                                      </button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="category">Paquete</label>
                                <div class="input-group">
                                    <ui-select ng-model="product.package_id" theme="bootstrap">
                                        <ui-select-match placeholder="Seleccione un paquete">
                                            <span ng-bind="$select.selected.name"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="package.id as package in (packages | filter: $select.search) track by package.id">
                                            <span>{{ package.id }} - {{ package.name }}</span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <span class="input-group-btn">
                                      <button type="button" ng-click="product.category_id = null" class="btn btn-default">
                                          <span class="glyphicon glyphicon-trash"></span>
                                      </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" ng-class="{active: activeTab == 'attributes'}">
                    <div class="form-horizontal">
                        <div class="form-group" ng-repeat="attribute in product.attributes | filter: { visible: true }">
                            <label class="col-sm-3 control-label">
                                <p>{{ attribute.name }}</p>
                                <small ng-bind-html="attribute.description"></small>
                            </label>
                            <div class="col-sm-7">
                                <languageable languages="languages" languageable-source="{ data: attribute.data }">
                                    <textarea class="form-control" ng-model="data.value"></textarea>
                                </languageable>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" ng-class="{active: activeTab == 'locations'}">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Localidades</h3>
                            <location-picker locations="locations" selected-locations="product.locationsIds" selected-locations-prices="product.locationsPrices"></location-picker>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" ng-class="{active: activeTab == 'images'}">
                    <div class="row">
                        <div class="col-sm-4" ng-repeat="image in product.images track by $index">
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
<script type="text/ng-template" id="location-picker-template.html">
    <ul class="location-list">
        <location-picker-item
            ng-repeat="location in locations track by $index"
            location="location"
            selected-locations="selectedLocations"
            selected-locations-prices="selectedLocationsPrices">
        </location-picker-item>
    </ul>
</script>

<script type="text/ng-template" id="location-picker-item-template.html">
    <li ng-class="{'has-children': location.all_children.length}">
        <div class="form-inline">
            <div class="checkbox">
                <a ng-if="location.all_children.length" ng-click="toggleChildrenDisplay()" class="glyphicon" ng-class="{ 'glyphicon-minus': showChildren, 'glyphicon-plus': !showChildren }"></a>
                <label>
                    <input type="checkbox" ng-checked="selectedLocations.indexOf(location.id) >= 0" ng-click="toggleLocation()">
                    <span>{{ location.name }}</span>
                </label>
            </div>
            <div class="form-group">
                <button ng-disabled="selectedLocations.indexOf(location.id) < 0" type="button" ng-show="!showCustomPrice" class="btn btn-default" ng-click="showCustomPrice = true">$</button>
                <div ng-show="showCustomPrice" class="input-group">
                    <span class="input-group-btn">
                        <button class="btn btn-success" type="button" ng-click="showCustomPrice = false">Precio</button>
                    </span>
                    <input type="text" ng-model="selectedLocationsPrices[location.id].price" class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-danger" type="button" ng-click="removePrice()"><span class="glyphicon glyphicon-trash"></span></button>
                    </span>
                </div>
                <div ng-show="showCustomPrice" class="input-group">
                    <span class="input-group-btn">
                        <button class="btn btn-success" type="button" ng-click="showCustomPrice = false">Precio Arriendo</button>
                    </span>
                    <input type="text" ng-model="selectedLocationsPrices[location.id].leasing_price" class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-danger" type="button" ng-click="removeLeasingPrice()"><span class="glyphicon glyphicon-trash"></span></button>
                    </span>
                </div>
            </div>
        </div>
    </li>
</script>