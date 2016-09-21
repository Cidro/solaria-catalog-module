<script>
    var contents = <?=json_encode([
        'currency' => $currency->toArray()
    ]);?>;
</script>
<div ng-controller="CatalogModuleCurrencyFormController" ng-init="init()" ng-cloak>
    <form ng-submit="submit()">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" ng-model="currency.default">
                            Principal
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" id="name" class="form-control" ng-model="currency.name">
                </div>
                <div class="form-group">
                    <label for="code">Código</label>
                    <input type="text" id="code" class="form-control" ng-model="currency.code">
                </div>
                <div class="form-group">
                    <label for="precision">Precisión</label>
                    <input type="text" id="precision" class="form-control" ng-model="currency.precision">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="symbol">Símbolo</label>
                    <input type="text" id="symbol" class="form-control" ng-model="currency.symbol">
                </div>
                <div class="form-group">
                    <label for="thousands_separator">Separador de miles</label>
                    <input type="text" id="thousands_separator" class="form-control" ng-model="currency.thousands_separator">
                </div>
                <div class="form-group">
                    <label for="decimal_point">Separador de decimales</label>
                    <input type="text" id="decimal_point" class="form-control" ng-model="currency.decimal_point">
                </div>
                <div class="form-group">
                    <label for="value">Value</label>
                    <input type="text" id="value" class="form-control" ng-model="currency.value">
                </div>
            </div>
        </div>
        <button class="btn btn-primary">Guardar</button>
    </form>
</div>