<script>
    var contents = <?=json_encode([
        'location' => $location->toArray(),
        'locations' => $locations->toArray(),
        'languages' => $languages->toArray()
    ]);?>;
</script>
<div ng-controller="CatalogModuleLocationFormController" ng-init="init()" ng-cloak>
    <form ng-submit="submit()">
        <div>
            <ul class="nav nav-tabs">
                <li ng-class="{active: activeTab == 'general'}">
                    <a href="#" ng-click="setActiveTab('general')">
                        <span class="glyphicon glyphicon-list-alt"></span> General
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" ng-class="{active: activeTab == 'general'}">
                    <div class="row">
                        <fieldset class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <languageable languages="languages" languageable-source="{ data: location.data }">
                                    <input type="text" id="name" class="form-control" ng-model="data.name">
                                </languageable>
                            </div>
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <languageable languages="languages" languageable-source="{ data: location.data, tinymceOptions: tinymceOptions }">
                                    <textarea ui-tinymce="tinymceOptions" class="form-control" ng-model="data.description"></textarea>
                                </languageable>
                            </div>
                        </fieldset>
                        <fieldset class="col-sm-6">
                            <legend>Configuración</legend>
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input type="text" id="codigo" class="form-control" ng-model="location.code">
                            </div>
                            <legend>Relaciones</legend>
                            <div class="form-group">
                                <label for="parent">Localidad Padre</label>
                                <div class="input-group">
                                    <ui-select ng-model="location.parent_id" theme="bootstrap">
                                        <ui-select-match placeholder="Seleccione una localidad padre">
                                            <span ng-bind="$select.selected.name"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="parent.id as parent in (locations | filter: $select.search) track by parent.id">
                                            <span>{{ parent.id }} - {{ parent.name }}</span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <span class="input-group-btn">
                                      <button type="button" ng-click="location.parent_id = null" class="btn btn-default">
                                          <span class="glyphicon glyphicon-trash"></span>
                                      </button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="layout">Sub Localidades</label>
                                <div class="form-group">
                                    <ui-select multiple ng-model="location.childrenIds" theme="bootstrap" ng-disabled="disabled">
                                        <ui-select-match placeholder="Seleccione los localidades">{{$item.name}}</ui-select-match>
                                        <ui-select-choices repeat="location.id as location in (locations | filter: $select.search)">
                                            <div ng-bind-html="location.name | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        <button class="pull-right btn btn-primary">Guardar</button>
    </form>
</div>