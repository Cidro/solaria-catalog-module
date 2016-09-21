<script>
    var contents = <?=json_encode([
        'attributes' => $attributes->toArray(),
        'attributesGroups' => $attributesGroups->toArray()
    ]);?>;
</script>
<div ng-controller="CatalogModuleAttributesController" ng-init="init()">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" ng-class="{active: activeTab == 'groups'}">
            <a href="#" ng-click="changeActiveTab('groups')"><span class="glyphicon glyphicon-list-alt"></span> Grupos</a>
        </li>
        <li role="presentation" ng-class="{active: activeTab == 'attributes'}">
            <a href="#" ng-click="changeActiveTab('attributes')"><span class="glyphicon glyphicon-list-alt"></span> Atributos</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" ng-class="{active: activeTab == 'groups'}">
            <div class="col-sm-8">
                <form class="form-filters">
                    <fieldset>
                        <legend>Filtros</legend>
                        <div class="form-group">
                            <label for="attribute-search">Búsqueda</label>
                            <input type="text" class="form-control" ng-model="filters.search">
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="col-sm-4">
                <a href="<?= url('backend/modules/catalog/attributes/create-group'); ?>" class="btn btn-primary">
                    <span class="glyphicon glyphicon-plus"></span> Nuevo Grupo de Attributos
                </a>
            </div>
            <div class="col-sm-12" ng-cloak>
                <div class="text-center"><dir-pagination-controls pagination-id="attributes-groups"></dir-pagination-controls></div>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th width="1"><input type="checkbox" ng-model="filters.selectAllAttributesGroups"></th>
                        <th width="1">Id</th>
                        <th>Nombre</th>
                        <th>Alias</th>
                        <th width="1">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr pagination-id="attributes-groups" dir-paginate="attributeGroup in attributesGroups | filter: filters.search | itemsPerPage: 25 track by $index ">
                        <td nowrap><input type="checkbox" ng-model="attributeGroup.selected" ng-change="toggleSelectedAttributeGroup($index)" /></td>
                        <td nowrap>{{ attributeGroup.id }}</td>
                        <td>{{ attributeGroup.name }}</td>
                        <td>{{ attributeGroup.alias }}</td>
                        <td nowrap>
                            <a title="Editar" data-toggle="tooltip" href="{{ baseUrl }}backend/modules/catalog/attributes/edit-group/{{ attributeGroup.id }}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-edit"></span></a>
                            <a title="Eliminar" data-toggle="tooltip" class="btn btn-xs btn-danger" ng-click="deleteAttributeGroup(attributeGroup)"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane" ng-class="{active: activeTab == 'attributes'}">
            <div class="col-sm-8">
                <form class="form-filters">
                    <fieldset>
                        <legend>Filtros</legend>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="attribute-search">Búsqueda</label>
                                <input type="text" class="form-control" ng-model="filters.search">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="category-id">Grupo</label>
                                <ui-select ng-model="filters.group" theme="bootstrap">
                                    <ui-select-match placeholder="Seleccione un Grupo">
                                        <span ng-bind="$select.selected.name"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="attributeGroup in (attributesGroups | filter: $select.search)">
                                        <span>{{ attributeGroup.id }} - {{ attributeGroup.name }}</span>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="col-sm-4">
                <a href="<?= url('backend/modules/catalog/attributes/create'); ?>" class="btn btn-primary">
                    <span class="glyphicon glyphicon-plus"></span> Nuevo Atributo
                </a>
            </div>
            <div class="col-sm-12" ng-cloak>
                <div class="text-center"><dir-pagination-controls pagination-id="attributes"></dir-pagination-controls></div>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="1"><input type="checkbox" ng-model="filters.selectAllAttributes"></th>
                            <th width="1">Id</th>
                            <th>Nombre</th>
                            <th>Alias</th>
                            <th>Descripción</th>
                            <th>Grupos</th>
                            <th width="1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr pagination-id="attributes" dir-paginate="attribute in attributes | filter: filters.search | filter: groupFilter | itemsPerPage: 25 track by $index ">
                            <td nowrap><input type="checkbox" ng-model="attribute.selected" ng-change="toggleSelected($index)" /></td>
                            <td nowrap>{{ attribute.id }}</td>
                            <td>{{ attribute.name }}</td>
                            <td>{{ attribute.alias }}</td>
                            <td ng-bind-html="attribute.description"></td>
                            <td>{{ groupsNames(attribute) }}</td>
                            <td nowrap>
                                <a title="Editar" data-toggle="tooltip" href="{{ baseUrl }}backend/modules/catalog/attributes/edit/{{ attribute.id }}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-edit"></span></a>
                                <a title="Eliminar" data-toggle="tooltip" class="btn btn-xs btn-danger" ng-click="deleteAttribute(attribute)"><span class="glyphicon glyphicon-trash"></span></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>