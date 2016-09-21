<script>
    var contents = <?=json_encode([
        'attribute' => $attribute->toArray(),
        'languages' => $languages->toArray(),
        'attributesGroups' => $attributesGroups->toArray()
    ]);?>;
</script>
<div ng-controller="CatalogModuleAttributeFormController" ng-init="init()" ng-cloak>
    <form ng-submit="submit()">
        <div class="row">
            <div class="col-sm-12">
                <fieldset class="col-sm-6">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <languageable languages="languages" languageable-source="{ data: attribute.data }">
                            <input type="text" id="name" class="form-control" ng-model="data.name">
                        </languageable>
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <languageable languages="languages" languageable-source="{ data: attribute.data, tinymceOptions: tinymceOptions }">
                            <textarea ui-tinymce="tinymceOptions" class="form-control" ng-model="data.description"></textarea>
                        </languageable>
                    </div>
                </fieldset>
                <fieldset class="col-sm-6">
                    <div class="form-group">
                        <label>Grupos de Atributos</label>
                        <ui-select multiple ng-model="attribute.attributesGroupsIds" theme="bootstrap" ng-disabled="disabled">
                            <ui-select-match placeholder="Seleccione un grupo de atributos">{{$item.name}}</ui-select-match>
                            <ui-select-choices repeat="attributeGroup.id as attributeGroup in (attributesGroups | filter: $select.search)">
                                <div ng-bind-html="attributeGroup.name | highlight: $select.search"></div>
                            </ui-select-choices>
                        </ui-select>
                    </div>
                </fieldset>
            </div>
        </div>
        <button class="btn btn-primary">Guardar</button>
    </form>
</div>