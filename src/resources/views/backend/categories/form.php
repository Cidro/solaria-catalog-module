<script>
    var contents = <?=json_encode([
        'category' => $category->toArray(),
        'categories' => $categories->toArray(),
        'languages' => $languages->toArray(),
        'layouts' => $layouts->toArray(),
        'pages' => $pages->toArray(),
        'attributesGroups' => $attributesGroups->toArray(),
        'isCopy' => $isCopy
    ]);?>;
</script>
<div ng-controller="CatalogModuleCategoryFormController" ng-init="init()" ng-cloak>
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
                                <languageable languages="languages" languageable-source="{ data: category.data }">
                                    <input type="text" id="name" class="form-control" ng-model="data.name">
                                </languageable>
                            </div>
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <languageable languages="languages" languageable-source="{ data: category.data, tinymceOptions: tinymceOptions }">
                                    <textarea ui-tinymce="tinymceOptions" class="form-control" ng-model="data.description"></textarea>
                                </languageable>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="category.is_visible" ng-value="true">
                                    Visible
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="page">Página</label>
                                <ui-select ng-model="category.page_id" theme="bootstrap" ng-change="updateProductAttributes()">
                                    <ui-select-match placeholder="Seleccione una página">
                                        <span ng-bind="$select.selected.title"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="page.id as page in (pages | filter: $select.search) track by page.id">
                                        <span>{{ page.id }} - {{ page.title }}</span>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                            <div class="form-group">
                                <label for="layout">Categoría padre</label>
                                <ui-select ng-change="changeParentCategory()" ng-model="category.parent_id" theme="bootstrap">
                                    <ui-select-match placeholder="Seleccione una categoría padre">
                                        <span ng-bind="$select.selected.name"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="cat.id as cat in (categories | filter: $select.search) track by cat.id">
                                        <span ng-bind="cat.name"></span>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                            <div class="form-group">
                                <label for="layout">Plantilla</label>
                                <ui-select ng-model="category.layout_id" theme="bootstrap">
                                    <ui-select-match placeholder="Seleccione una plantilla">
                                        <span ng-bind="$select.selected.name"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="layout.id as layout in (layouts | filter: $select.search) track by layout.id">
                                        <span ng-bind="layout.name"></span>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                            <div class="form-group">
                                <label for="layout">Plantilla para productos</label>
                                <ui-select ng-model="category.product_layout_id" theme="bootstrap">
                                    <ui-select-match placeholder="Seleccione una plantilla">
                                        <span ng-bind="$select.selected.name"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="layout.id as layout in (layouts | filter: $select.search) track by layout.id">
                                        <span ng-bind="layout.name"></span>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                            <div class="form-group">
                                <label for="layout">Plantilla para paquetes</label>
                                <ui-select ng-model="category.package_layout_id" theme="bootstrap">
                                    <ui-select-match placeholder="Seleccione una plantilla">
                                        <span ng-bind="$select.selected.name"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="layout.id as layout in (layouts | filter: $select.search) track by layout.id">
                                        <span ng-bind="layout.name"></span>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                            <fieldset>
                                <legend>Grupos de Attributos asociados</legend>
                                <div class="form-group">
                                    <ui-select multiple ng-model="category.attributesGroupsIds" theme="bootstrap" ng-disabled="disabled">
                                        <ui-select-match placeholder="Seleccione un grupo de atributos">{{$item.name}}</ui-select-match>
                                        <ui-select-choices repeat="attributeGroup.id as attributeGroup in (attributesGroups | filter: $select.search)">
                                            <div ng-bind-html="attributeGroup.name | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" ng-class="{active: activeTab == 'images'}">
                    <div class="row">
                        <div class="col-sm-4" ng-repeat="image in category.images track by $index">
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