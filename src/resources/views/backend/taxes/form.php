<script>
    var contents = <?=json_encode([
        'tax' => $tax->toArray()
    ]);?>;
</script>
<div ng-controller="CatalogModuleTaxFormController" ng-init="init()" ng-cloak>
    <form ng-submit="submit()">
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" ng-model="tax.default">
                    Principal
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" id="name" class="form-control" ng-model="tax.name">
        </div>
        <div class="form-group">
            <label for="value">Value</label>
            <input type="text" id="value" class="form-control" ng-model="tax.value">
        </div>
        <button class="btn btn-primary">Guardar</button>
    </form>
</div>