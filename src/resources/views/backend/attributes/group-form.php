<script>
    var contents = <?=json_encode([
        'attributeGroup' => $attributeGroup->toArray(),
        'attributes' => $attributes->toArray(),
        'languages' => $languages->toArray()
    ]);?>;
</script>
<div ng-controller="CatalogModuleAttributeGroupFormController" ng-init="init()" ng-cloak>
    <form ng-submit="submit()">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <languageable languages="languages" languageable-source="{ data: attributeGroup.data }">
                        <input type="text" id="name" class="form-control" ng-model="data.name">
                    </languageable>
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <languageable languages="languages" languageable-source="{ data: attributeGroup.data, tinymceOptions: tinymceOptions }">
                        <textarea ui-tinymce="tinymceOptions" class="form-control" ng-model="data.description"></textarea>
                    </languageable>
                </div>
            </div>
            <div class="col-sm-6">
                <fieldset>
                    <legend>Attributos asociados</legend>
                    <div class="form-group">
                        <div class="input-group">
                            <ui-select multiple ng-model="attributeGroup.attributesIds" theme="bootstrap" ng-disabled="disabled">
                                <ui-select-match placeholder="Seleccione un atributo">{{$item.name}}</ui-select-match>
                                <ui-select-choices repeat="attribute.id as attribute in (attributes | filter: $select.search)">
                                    <div ng-bind-html="attribute.name | highlight: $select.search"></div>
                                    <strong ng-bind-html="attribute.description"></strong>
                                </ui-select-choices>
                            </ui-select>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" ng-click="addNewTag = true">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div ng-if="addNewTag" class="well">
                        <div class="form-group">
                            <label>Nombre</label>
                            <languageable languages="languages" languageable-source="{ data: newAttribute.data }">
                                <input type="text" id="name" class="form-control" ng-model="data.name">
                            </languageable>
                        </div>
                        <button type="button" ng-click="addNewAttribute()" class="btn btn-default">Guardar Nuevo Attributo</button>
                    </div>
                </fieldset>
            </div>
        </div>
        <button class="btn btn-primary">Guardar</button>
    </form>
</div>