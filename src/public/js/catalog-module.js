(function (global, angular, $, solaria) {
    var tinymceDefaultOptions = {
        menubar: false,
        plugins: "image code link table solarialink paste",
        toolbar: [
            "styleselect formatselect | undo redo | bold italic underline strikethrough removeformat",
            "alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | table image code solarialink unlink"
        ],
        convert_urls: false,
        min_height: 300,
        image_advtab: true,
        auto_focus: true,
        entities: '',
        entity_encoding: 'raw',
        link_list: baseUrl + 'backend/pages/pages-list',
        paste_as_text: true
    };

    var moduleBaseUrl = baseUrl.replace(/\/+$/g,"") + '/backend/modules/catalog/';

    solaria.controller('CatalogModuleController', function ($scope, $http, $solariaMessenger) {
        $scope.init = function(){
            $scope.baseUrl = baseUrl;
            $scope.products = contents.products;
        }
    });

    solaria.controller('CatalogModuleCategoriesController', function($scope, $http, $solariaMessenger){
        $scope.init = function(){
            $scope.categories = contents.categories;
            $scope.baseUrl = baseUrl;
        };

        $scope.deleteCategory = function(category){
            if(confirm('Esta seguro que desea eliminar la categoría [' + category.name + ']')){
                $http.get(moduleBaseUrl + 'categories/delete/' + category.id)
                    .success(function(response){
                        $solariaMessenger.showMessage(response.message, 'success');
                        $scope.categories.splice(Helpers.findKey($scope.categories, category.id), 1);
                    });
            }
        };

        $scope.getCategoryName = function(category){
            var name = category.name;
            if(category.parent_id)
                name = $scope.getCategoryName(Helpers.findItem($scope.categories, category.parent_id)) + ' - ' + name;
            return name;
        };
    });

    solaria.controller('CatalogModuleCategoryFormController', function($scope, $http, $solariaMessenger){
        var initializeCategory = function(category){
            category.data = {
                name: '',
                description: ''
            };
            category.attributes_groups = [];
            category.attributesGroupsIds = [];
            category.images = [];
        };

        var prepareCategory = function(category){
            var attributesGroupsIds = [];
            for(var ag in category.attributes_groups){
                attributesGroupsIds.push(category.attributes_groups[ag].id);
            }
            category.attributesGroupsIds = attributesGroupsIds;
            if(!category.images)
                category.images = [];
            return category;
        };

        $scope.init = function(){
            if(!contents.category.id)
                initializeCategory(contents.category);

            if(contents.isCopy)
                contents.category.id = null;

            $scope.category = prepareCategory(angular.copy(contents.category));
            $scope.categories = contents.categories;
            $scope.languages = contents.languages;
            $scope.attributesGroups = contents.attributesGroups;
            $scope.layouts = contents.layouts;
            $scope.pages = contents.pages;
            $scope.activeTab = 'general';
        };

        $scope.submit = function(){
            $http.post(moduleBaseUrl + 'categories/save', $scope.category)
                .success(function(response){
                    if(!$scope.category.id)
                        $scope.category.id = response.category.id;
                    $solariaMessenger.showMessage(response.message, 'success');
                })
                .error(function(errors, code){
                    $solariaMessenger.showErrors(errors);
                });
        };

        $scope.changeParentCategory = function(){
            var parentCategory = Helpers.findItem($scope.categories, $scope.category.parent_id);
            if(!$scope.category.layout_id)
                $scope.category.layout_id = parentCategory.layout_id;
            if(!$scope.category.product_layout_id)
                $scope.category.product_layout_id = parentCategory.product_layout_id;
            if(!$scope.category.package_layout_id)
                $scope.category.package_layout_id = parentCategory.package_layout_id;
        };

        $scope.setActiveTab = function(tab){
            $scope.activeTab = tab;
        };

        $scope.addImage = function(){
            $scope.category.images.push({
                type: 'image',
                label: 'Imagen',
                config: {}
            });
            if($scope.category.images.length == 1)
                $scope.category.images[0].default = true;
        };

        $scope.changeMainImage = function($index){
            for(var i in $scope.category.images){
                if(i != $index)
                    $scope.category.images[i].default = false;
            }
        };

        $scope.removeImage = function($index){
            var wasDefault = $scope.product.images[$index].default && $scope.product.images.length > 1;
            $scope.product.images.splice($index,1);
            if(wasDefault)
                $scope.product.images[0].default = true;
        };
    });

    solaria.controller('CatalogModuleAttributesController', function($scope, $http, $solariaMessenger){
        var prepareAttributes = function(attributes){
            if(attributes && attributes.length){
                attributes.forEach(function(attribute){
                    var attributesGroupsIds = [];
                    if(attribute.groups && attribute.groups.length){
                        attribute.groups.forEach(function(group){
                            attributesGroupsIds.push(group.id);
                        });
                    }
                    attribute.attributesGroupsIds = attributesGroupsIds;
                });
            }
            return attributes;
        };
        $scope.init = function(){
            $scope.filters = {
                group: ''
            };
            $scope.attributes = prepareAttributes(angular.copy(contents.attributes));
            $scope.attributesGroups = contents.attributesGroups;
            $scope.baseUrl = baseUrl;
            $scope.activeTab = 'groups';
        };

        $scope.groupFilter = function(value){
            if($scope.filters.group)
                return value.attributesGroupsIds.indexOf($scope.filters.group.id) >= 0;

            return true;
        };

        $scope.deleteAttribute = function(attribute){
            if(confirm('Esta seguro que desea eliminar el atributo [' + attribute.name + ']')){
                $http.get(moduleBaseUrl + 'attributes/delete/' + attribute.id)
                    .success(function(response){
                        $solariaMessenger.showMessage(response.message, 'success');
                        $scope.attributes.splice(Helpers.findKey($scope.attributes, attribute.id), 1);
                    });
            }
        };

        $scope.groupsNames = function(attribute){
            if(!attribute.groups || !attribute.groups.length)
                return '-';
            var names = [];
            attribute.groups.forEach(function(group){
                names.push(group.name);
            });
            return names.join(' - ');
        };

        $scope.deleteAttributeGroup = function(attributeGroup){
            if(confirm('Esta seguro que desea eliminar el grupo de atributos [' + attributeGroup.name + ']')){
                $http.get(moduleBaseUrl + 'attributes/delete-group/' + attributeGroup.id)
                    .success(function(response){
                        $solariaMessenger.showMessage(response.message, 'success');
                        $scope.attributesGroups.splice(Helpers.findKey($scope.attributesGroups, attributeGroup.id), 1);
                    });
            }
        };

        $scope.changeActiveTab = function(tab){
            $scope.activeTab = tab;
        };
    });

    solaria.controller('CatalogModuleAttributeFormController', function($scope, $http, $solariaMessenger){
        var initializeAttribute = function(attribute){
            attribute.data = {
                name: '',
                description: ''
            };
        };

        var prepareAttribute = function(attribute){
            var attributesGroupsIds = [];
            if(attribute.groups && attribute.groups.length) {
                attribute.groups.forEach(function (item, index) {
                    attributesGroupsIds.push(item.id);
                });
            }
            attribute.attributesGroupsIds = attributesGroupsIds;
            return attribute;
        };

        $scope.init = function(){
            if(!contents.attribute.id)
                initializeAttribute(contents.attribute);

            $scope.attribute = prepareAttribute(angular.copy(contents.attribute));
            $scope.attributesGroups = contents.attributesGroups;
            $scope.languages = contents.languages;
        };

        $scope.submit = function(){
            $http.post(moduleBaseUrl + 'attributes/save', $scope.attribute)
                .success(function(response){
                    if(!$scope.attribute.id)
                        $scope.attribute.id = response.attribute.id;
                    $solariaMessenger.showMessage(response.message, 'success');
                })
                .error(function(errors, code){
                    $solariaMessenger.showErrors(errors);
                });
        };
    });

    solaria.controller('CatalogModuleAttributeGroupFormController', function($scope, $http, $solariaMessenger){
        var initializeAttributeGroup = function(attributeGroup){
            attributeGroup.data = {
                name: '',
                description: '',
                attributes: [],
                attributesIds: []
            };
        };

        var prepareAttributeGroup = function(attributeGroup){
            var attributesIds = [];
            for(var a  in attributeGroup.attributes){
                attributesIds.push(attributeGroup.attributes[a].id);
            }
            attributeGroup.attributesIds = attributesIds;
            return attributeGroup;
        };

        $scope.init = function(){
            if(!contents.attributeGroup.id)
                initializeAttributeGroup(contents.attributeGroup);

            $scope.addNewTag = false;
            $scope.attributeGroup = prepareAttributeGroup(angular.copy(contents.attributeGroup));
            $scope.attributes = contents.attributes;
            $scope.languages = contents.languages;
            $scope.newAttribute = {data: {}, site_id: $scope.attributeGroup.site_id};
        };

        $scope.submit = function(){
            $http.post(moduleBaseUrl + 'attributes/save-group', $scope.attributeGroup)
                .success(function(response){
                    if(!$scope.attributeGroup.id)
                        $scope.attributeGroup.id = response.attributeGroup.id;
                    $solariaMessenger.showMessage(response.message, 'success');
                })
                .error(function(errors, code){
                    $solariaMessenger.showErrors(errors);
                });
        };

        $scope.addNewAttribute = function(){
            $http.post(moduleBaseUrl + 'attributes/quick-add-attribute', $scope.newAttribute)
                .success(function(response){
                    $solariaMessenger.showMessage(response.message, 'success');
                    $scope.attributes.push(response.attribute);
                    $scope.attributeGroup.attributesIds.push(response.attribute.id);
                    $scope.newAttribute = {data: {}, site_id: $scope.attributeGroup.site_id};
                    $scope.addNewTag = false;
                })
                .error(function(errors, code){
                    $solariaMessenger.showErrors(errors);
                });
        };
    });

    solaria.controller('CatalogModuleProductsController', function($scope, $http, $solariaMessenger){
        var getChildrenIds = function(categories){
            var ids = [];
            if(categories.length){
                for(var c in categories){
                    ids = ids.concat([categories[c].id]).concat(getChildrenIds(categories[c].children));
                }
            }
            return ids;
        };
        var prepareCategories = function(categories){
            for(var c in categories){
                categories[c].ids = [categories[c].id].concat(getChildrenIds(categories[c].children));
            }
            return categories;
        };

        $scope.categoryFilter = function(value){
            if($scope.filters.categories.length)
                return $scope.filters.categories.indexOf(value.category_id) !== -1;
            return true;
        };

        $scope.init = function(){
            $scope.products = contents.products;
            $scope.categories = prepareCategories(angular.copy(contents.categories));
            $scope.filters = {
                categories: []
            };
            $scope.baseUrl = baseUrl;
        };

        $scope.deleteProduct = function(product){
            if(confirm('Esta seguro que desea eliminar el producto [' + product.name + ']')){
                $http.get(moduleBaseUrl + 'products/delete/' + product.id)
                    .success(function(response){
                        $solariaMessenger.showMessage(response.message, 'success');
                        $scope.products.splice(Helpers.findKey($scope.products, product.id), 1);
                    });
            }
        };

        $scope.getCategoryName = function(category){
            var name = category.name;
            if(category.parent_id)
                name = $scope.getCategoryName(Helpers.findItem($scope.categories, category.parent_id)) + ' - ' + name;
            return name;
        };
    });

    solaria.controller('CatalogModuleProductFormController', function($scope, $http, $solariaMessenger){
        var initializeProduct = function(product){
            product.data = {
                name: '',
                description: ''
            };
            product.price = '';
            product.sku = '';
            product.code = '';
            product.category = null;
            product.attributes = [];
            product.images = [];
        };

        var isAttributeAvailable = function(category, attribute){
            if(category){
                for(var ag in category.attributes_groups){
                    if(Helpers.findKey(category.attributes_groups[ag].attributes, attribute.id) != null)
                        return true;
                }
                if(category.parent_id){
                    return isAttributeAvailable(Helpers.findItem($scope.categories, category.parent_id), attribute);
                }
            }
            return false;
        };

        var updateProductAttributes = function(){
            for(var a in $scope.attributes){
                var attributeIndex = Helpers.findKey($scope.product.attributes, $scope.attributes[a].id);
                if(attributeIndex == null){
                    $scope.product.attributes.push(angular.copy($scope.attributes[a]));
                    attributeIndex = $scope.product.attributes.length - 1;
                }
                $scope.product.attributes[attributeIndex].visible = isAttributeAvailable($scope.product.category, $scope.attributes[a]);
            }
        };

        var mergeAttributesValues = function(attributes){
            var mergedAttributes = [];
            for(var i in attributes){
                var languageCode = attributes[i].language.code,
                    attributeKey = Helpers.findKey(mergedAttributes, attributes[i].attribute_id);
                if(attributeKey == null){
                    mergedAttributes.push(attributes[i].attribute);
                    attributeKey = mergedAttributes.length - 1;
                }
                mergedAttributes[attributeKey].data.translations[languageCode].data.value = attributes[i].value;
            }
            return mergedAttributes;
        };

        var prepareProduct = function(product){
            var locationsIds = [],
                locationsPrices = {};

            for(var i in product.locations){
                locationsIds.push(product.locations[i].id);
                locationsPrices[product.locations[i].id] = {
                    'price': product.locations[i].pivot.price,
                    'leasing_price': product.locations[i].pivot.leasing_price
                };
            }

            product.locationsIds = locationsIds;
            product.locationsPrices = locationsPrices;

            product.attributes = mergeAttributesValues(angular.copy(product.attributes));
            if(!product.images || !(product.images instanceof Array))
                product.images = [];

            return product;

        };

        $scope.init = function(){
            if(!contents.product.id)
                initializeProduct(contents.product);

            if(contents.isCopy)
                contents.product.id = null;

            $scope.product = prepareProduct(contents.product);
            $scope.products = contents.products;
            $scope.locations = contents.locations;
            $scope.languages = contents.languages;
            $scope.categories = contents.categories;
            $scope.attributes = contents.attributes;
            $scope.pages = contents.pages;
            $scope.packages = contents.packages;
            $scope.availableAttributes = [];
            $scope.tinymceOptions = tinymceDefaultOptions;
            updateProductAttributes();

            $scope.activeTab = 'general';

            $scope.$watch('product.category_id', function(newValue, oldValue){
                $scope.product.category = Helpers.findItem($scope.categories, newValue);
                updateProductAttributes();
            });
        };

        $scope.submit = function(){
            $http.post(moduleBaseUrl + 'products/save', $scope.product)
                .success(function(response){
                    if(!$scope.product.id)
                        $scope.product.id = response.product.id;
                    $solariaMessenger.showMessage(response.message, 'success');
                })
                .error(function(errors, code){
                    $solariaMessenger.showErrors(errors);
                });
        };

        $scope.setActiveTab = function(tab){
            $scope.activeTab = tab;
        };

        $scope.hasCategory = function(category){
            return Helpers.findKey($scope.product.categories, category.id);
        };

        $scope.addImage = function(){
            $scope.product.images.push({
                type: 'image',
                label: 'Imagen',
                config: {}
            });
            if($scope.product.images.length == 1)
                $scope.product.images[0].default = true;
        };

        $scope.changeMainImage = function($index){
            for(var i in $scope.product.images){
                if(i != $index)
                    $scope.product.images[i].default = false;
            }
        };

        $scope.removeImage = function($index){
            var wasDefault = $scope.product.images[$index].default && $scope.product.images.length > 1;
            $scope.product.images.splice($index,1);
            if(wasDefault)
                $scope.product.images[0].default = true;
        };
    });

    solaria.controller('CatalogModulePackageFormController', function($scope, $http, $solariaMessenger){
        var initializePackage = function(productsPackage){
            productsPackage.data = {
                name: '',
                description: '',
            };
            productsPackage.price = 0;
            productsPackage.productsIds = [];
        };

        var preparePackage = function(productsPackage){
            var finalProducts = [],
                productsIds = [],
                childPackagesIds = [];
            for(var p in productsPackage.products){
                if(productsIds.indexOf(productsPackage.products[p].id) < 0){
                    productsIds.push(productsPackage.products[p].id);
                    finalProducts.push(productsPackage.products[p]);
                }
            }
            for(var i in productsPackage.child_packages){
                childPackagesIds.push(productsPackage.child_packages[i].id);
            }

            productsPackage.productsIds = productsIds;
            productsPackage.childPackagesIds = childPackagesIds;

            if(!productsPackage.images)
                productsPackage.images = [];

            productsPackage.products = finalProducts;
            return productsPackage;
        };

        $scope.init = function(){
            if(!contents.package.id)
                initializePackage(contents.package);

            $scope.package = preparePackage(angular.copy(contents.package));
            $scope.languages = contents.languages;
            $scope.packages = contents.packages;
            $scope.categories = contents.categories;
            $scope.products = contents.products;
            $scope.activeTab = 'general';
        };

        $scope.submit = function(){
            $http.post(moduleBaseUrl + 'packages/save', $scope.package)
                .success(function(response){
                    if(!$scope.package.id)
                        $scope.package.id = response.package.id;
                    $solariaMessenger.showMessage(response.message, 'success');
                })
                .error(function(errors, code){
                    $solariaMessenger.showErrors(errors);
                });
        };

        $scope.setActiveTab = function(tab){
            $scope.activeTab = tab;
        };

        $scope.addImage = function(){
            $scope.package.images.push({
                type: 'image',
                label: 'Imagen',
                config: {}
            });
            if($scope.package.images.length == 1)
                $scope.package.images[0].default = true;
        };

        $scope.changeMainImage = function($index){
            for(var i in $scope.package.images){
                if(i != $index)
                    $scope.package.images[i].default = false;
            }
        };

        $scope.removeImage = function($index){
            var wasDefault = $scope.product.images[$index].default && $scope.product.images.length > 1;
            $scope.product.images.splice($index,1);
            if(wasDefault)
                $scope.product.images[0].default = true;
        };
    });

    solaria.controller('CatalogModulePackagesController', function($scope, $http, $solariaMessenger){
        $scope.init = function(){
            $scope.packages = contents.packages;
            $scope.baseUrl = baseUrl;
        };

        $scope.deletePackage = function(productPackage){
            if(confirm('Esta seguro que desea eliminar el paquete [' + productPackage.name + ']')){
                $http.get(moduleBaseUrl + 'packages/delete/' + productPackage.id)
                    .success(function(response){
                        $solariaMessenger.showMessage(response.message, 'success');
                        $scope.packages.splice(Helpers.findKey($scope.packages, productPackage.id), 1);
                    });
            }
        }
    });

    solaria.controller('CatalogModuleLocationFormController', function($scope, $http, $solariaMessenger){
        var initializeLocation = function(location){
            location.data = {
                name: '',
                description: '',
            };
        };

        var prepareLocation = function(location){
            var childrenIds = [];

            for(var i in location.children)
                childrenIds.push(location.children[i].id);

            location.childrenIds = childrenIds;
            return location;
        };

        $scope.init = function(){
            if(!contents.location.id)
                initializeLocation(contents.location);

            $scope.location = prepareLocation(angular.copy(contents.location));
            $scope.locations = contents.locations;
            $scope.languages = contents.languages;
            $scope.activeTab = 'general';
        };

        $scope.submit = function(){
            $http.post(moduleBaseUrl + 'locations/save', $scope.location)
                .success(function(response){
                    if(!$scope.location.id)
                        $scope.location.id = response.location.id;
                    $solariaMessenger.showMessage(response.message, 'success');
                })
                .error(function(errors, code){
                    $solariaMessenger.showErrors(errors.message);
                });
        };

        $scope.setActiveTab = function(tab){
            $scope.activeTab = tab;
        };
    });

    solaria.controller('CatalogModuleLocationsController', function($scope, $http, $solariaMessenger){
        $scope.init = function(){
            $scope.locations = contents.locations;
            $scope.baseUrl = baseUrl;
        };

        $scope.deleteLocation = function(location){
            if(confirm('Esta seguro que desea eliminar la localidad [' + location.name + ']')){
                $http.get(moduleBaseUrl + 'locations/delete/' + location.id)
                    .success(function(response){
                        $solariaMessenger.showMessage(response.message, 'success');
                        $scope.locations.splice(Helpers.findKey($scope.locations, location.id), 1);
                    });
            }
        };

        $scope.getLocationName = function(location){
            var name = location.name;
            if(location.parent_id)
                name = $scope.getLocationName(Helpers.findItem($scope.locations, location.parent_id)) + ' - ' + name;
            return name;
        };
    });

    solaria.controller('CatalogModuleCurrenciesController', function($scope, $http, $solariaMessenger){
        $scope.init = function(){
            $scope.currencies = contents.currencies;
            $scope.baseUrl = baseUrl;
        };

        $scope.deleteCurrency = function(currency){
            if(confirm('Esta seguro que desea eliminar el paquete [' + currency.name + ']')){
                $http.get(moduleBaseUrl + 'currencies/delete/' + currency.id)
                    .success(function(response){
                        $solariaMessenger.showMessage(response.message, 'success');
                        $scope.currencies.splice(Helpers.findKey($scope.currencies, currency.id), 1);
                    });
            }
        }
    });

    solaria.controller('CatalogModuleCurrencyFormController', function($scope, $http, $solariaMessenger){
        var initializeAttribute = function(currency){
            angular.merge(currency, {
                default: false,
                code: '',
                name: '',
                precision: 0,
                symbol: '$',
                value: 1
            });
            return currency;
        };

        $scope.init = function(){
            if(!contents.currency.id)
                contents.currency = initializeAttribute(contents.currency);

            $scope.currency = contents.currency;
        };

        $scope.submit = function(){
            $http.post(moduleBaseUrl + 'currencies/save', $scope.currency)
                .success(function(response){
                    if(!$scope.currency.id)
                        $scope.currency.id = response.currency.id;
                    $solariaMessenger.showMessage(response.message, 'success');
                })
                .error(function(errors, code){
                    $solariaMessenger.showErrors(errors);
                });
        };
    });

    solaria.controller('CatalogModuleLayoutsController', function($scope, $http, $solariaMessenger){
        $scope.init = function(){
            $scope.layouts = contents.layouts;
            $scope.baseUrl = baseUrl;
        };

        $scope.deleteLayout = function(layout){
            if(confirm('Esta seguro que desea eliminar la plantilla [' + layout.name + ']')){
                $http.get(moduleBaseUrl + 'layouts/delete/' + layout.id)
                    .success(function(response){
                        $solariaMessenger.showMessage(response.message, 'success');
                        $scope.layouts.splice(Helpers.findKey($scope.layouts, layout.id), 1);
                    });
            }
        }
    });

    solaria.controller('CatalogModuleLayoutFormController', function($scope, $http, $solariaMessenger){
        var initializeLayout = function(layout){
            angular.merge(layout, {
                name: '',
                alias: '',
                html: ''
            });
            return layout;
        };

        $scope.$watch('layout.name', function(newValue, oldValue){
            if(newValue !== oldValue)
                $scope.layout.alias = newValue ? slug(newValue).toLowerCase() : '';
        });

        $scope.init = function(){
            if(!contents.layout.id)
                contents.layout = initializeLayout(contents.layout);

            $scope.layout = contents.layout;
            $scope.activeTab = 'general';
        };

        $scope.submit = function(){
            $http.post(moduleBaseUrl + 'layouts/save', $scope.layout)
                .success(function(response){
                    if(!$scope.layout.id)
                        $scope.layout.id = response.layout.id;
                    $solariaMessenger.showMessage(response.message, 'success');
                })
                .error(function(errors, code){
                    $solariaMessenger.showErrors(errors);
                });
        };

        $scope.setActiveTab = function(tab){
            $scope.activeTab = tab;
        }
    });

    solaria.controller('CatalogModuleTaxesController', function($scope, $http, $solariaMessenger){
        $scope.init = function(){
            $scope.taxes = contents.taxes;
            $scope.baseUrl = baseUrl;
        };

        $scope.deleteTax = function(tax){
            if(confirm('Esta seguro que desea eliminar el impuesto [' + tax.name + ']')){
                $http.get(moduleBaseUrl + 'taxes/delete/' + tax.id)
                    .success(function(response){
                        $solariaMessenger.showMessage(response.message, 'success');
                        $scope.taxes.splice(Helpers.findKey($scope.taxes, tax.id), 1);
                    });
            }
        }
    });

    solaria.controller('CatalogModuleTaxFormController', function($scope, $http, $solariaMessenger){
        var initializeAttribute = function(tax){
            angular.merge(tax, {
                default: false,
                value: 0
            });
            return tax;
        };

        $scope.init = function(){
            if(!contents.tax.id)
                contents.tax = initializeAttribute(contents.tax);

            $scope.tax = contents.tax;
        };

        $scope.submit = function(){
            $http.post(moduleBaseUrl + 'taxes/save', $scope.tax)
                .success(function(response){
                    if(!$scope.tax.id)
                        $scope.tax.id = response.tax.id;
                    $solariaMessenger.showMessage(response.message, 'success');
                })
                .error(function(errors, code){
                    $solariaMessenger.showErrors(errors);
                });
        };
    });

    solaria.directive('locationPicker', function () {
        return {
            restrict: 'E',
            templateUrl: 'location-picker-template.html',
            scope: {locations: '=', selectedLocations: '=', selectedLocationsPrices: '='}
        }
    });

    solaria.directive('locationPickerItem', function ($compile) {
        var hasPrice = function(locationId, locationsPrices){
            return locationsPrices.hasOwnProperty(locationId)
                && (
                    parseFloat(locationsPrices[locationId].price) != 0
                    || parseFloat(locationsPrices[locationId].leasing_price) != 0
                );
        };
        return {
            restrict: 'E',
            templateUrl: 'location-picker-item-template.html',
            replace: true,
            scope: {location: '=', selectedLocations: '=', selectedLocationsPrices: '='},
            link: function(scope, element){
                scope.showChildren = true;
                scope.showCustomPrice = hasPrice(scope.location.id, scope.selectedLocationsPrices);
                scope.isSelected = scope.selectedLocations.indexOf(scope.location.id) >= 0;
                var locationPickerString = '<location-picker ng-show="showChildren" locations="location.all_children" selected-locations="selectedLocations" selected-locations-prices="selectedLocationsPrices"></location-picker>';
                if(scope.location.all_children.length){
                    $compile(locationPickerString)(scope, function(cloned, scope)   {
                        element.append(cloned);
                    });
                }
            },
            controller: function($scope){
                $scope.toggleChildrenDisplay = function(){
                    $scope.showChildren = !$scope.showChildren;
                };
                $scope.toggleLocation = function(){
                    $scope.$broadcast('toggleLocation',{
                        check: $scope.selectedLocations.indexOf($scope.location.id) < 0
                    });
                };
                $scope.removePrice = function(){
                    $scope.selectedLocationsPrices[$scope.location.id].price = 0;
                    $scope.showCustomPrice = $scope.selectedLocationsPrices[$scope.location.id].price > 0
                                             || $scope.selectedLocationsPrices[$scope.location.id].leasing_price > 0;
                };
                $scope.removeLeasingPrice = function(){
                    $scope.selectedLocationsPrices[$scope.location.id].leasing_price = 0;
                    $scope.showCustomPrice = $scope.selectedLocationsPrices[$scope.location.id].price > 0
                                             || $scope.selectedLocationsPrices[$scope.location.id].leasing_price > 0;
                };
                $scope.$on('toggleLocation', function(e, options) {
                    if(options.check){
                        $scope.selectedLocations.push($scope.location.id);
                        $scope.showCustomPrice = hasPrice($scope.location.id, $scope.selectedLocationsPrices);
                    } else {
                        $scope.selectedLocations.splice($scope.selectedLocations.indexOf($scope.location.id), 1);
                        $scope.showCustomPrice = false;
                    }
                    e.preventDefault();
                });
            }
        }
    });
})(window, angular, jQuery, solaria);