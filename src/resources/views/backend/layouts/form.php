<script>
    var contents = <?=json_encode([
        'layout' => $layout->toArray()
    ]);?>;
</script>
<div ng-controller="CatalogModuleLayoutFormController" ng-init="init()" ng-cloak>
    <form ng-submit="submit()">
        <div>
            <ul class="nav nav-tabs">
                <li ng-class="{active: activeTab == 'general'}">
                    <a href="#" ng-click="setActiveTab('general')">
                        <span class="glyphicon glyphicon-list-alt"></span> General
                    </a>
                </li>
                <li ng-class="{active: activeTab == 'html'}">
                    <a href="#" ng-click="setActiveTab('html')">
                        <span class="glyphicon glyphicon-list-alt"></span> Html
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" ng-class="{active: activeTab == 'general'}">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" class="form-control" ng-model="layout.name">
                    </div>
                    <div class="form-group">
                        <label for="alias">Alias</label>
                        <input type="text" id="alias" class="form-control" ng-model="layout.alias" readonly>
                    </div>
                </div>
                <div class="tab-pane" ng-class="{active: activeTab == 'html'}">
                    <div ui-ace="{mode:'twig', theme:'monokai'}" ng-model="layout.html"></div>
                </div>
            </div>
        </div>
        <button class="btn btn-primary">Guardar</button>
    </form>
</div>