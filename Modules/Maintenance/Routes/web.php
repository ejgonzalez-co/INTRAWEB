<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// Route::group(['prefix' => 'maintenance' , 'middleware' => ['auth']], function () {
Route::group(['prefix' => 'maintenance'], function () {
    Route::middleware(['auth', 'verified'])->group(function () {

        Route::get('/', function () {
            dd('This is the Maintenance module index page. Build something great!');
        });

        Route::resource('asset-types', 'AssetTypeController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-asset-types', 'AssetTypeController@all')->name('all');

        // Ruta que obtiene los registros basandose en el activo
        Route::get('export-assetManagements-actives/{plaque}', 'AssetManagementController@exportMantenances')->name('exportMantenances');
        
        Route::post('export-asset-types', 'AssetTypeController@export')->name('export');

        Route::resource('categories', 'CategoryController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-categories', 'CategoryController@all')->name('all');

        Route::get('get-categories-full', 'CategoryController@allCategoryFull')->name('allCategoryFull');

        Route::post('export-categories', 'CategoryController@export')->name('export');

        Route::get('get-type-assets', 'AssetTypeController@all')->name('all');
        
        Route::get('get-type-assets-full', 'AssetTypeController@allAssetFull')->name('allAssetFull');

        Route::resource('asset-create-authorizations', 'AssetCreateAuthorizationController', ['only' => [
            'index', 'store', 'update', 'destroy', 'show'
        ]]);
        Route::get('get-asset-create-authorizations', 'AssetCreateAuthorizationController@all')->name('all');
        
        Route::post('export-asset-create-authorizations', 'AssetCreateAuthorizationController@export')->name('export');

        Route::resource('providers', 'ProvidersController', ['only' => [
            'index', 'store', 'update', 'destroy', 'show'
        ]]);
        Route::get('get-providers', 'ProvidersController@all')->name('all');
        
        Route::post('export-providers', 'ProvidersController@export')->name('export');

        Route::resource('supports-providers', 'SupportsProviderController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        Route::post('supports-providers/{mp}', ['as' => 'SupportsProvider.store', 'uses' => 'SupportsProviderController@store']);

        Route::get('get-supports-providers/{mp}', 'SupportsProviderController@all')->name('all');
        
        Route::post('export-supports-providers', 'SupportsProviderController@export')->name('export');

        Route::resource('types-activities', 'TypesActivityController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-types-activities', 'TypesActivityController@all')->name('all');
        
        Route::post('export-types-activities', 'TypesActivityController@export')->name('export');

        Route::resource('resume-machinery-vehicles-yellows', 'ResumeMachineryVehiclesYellowController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        Route::post('delete-validate-rubro','ResumeMachineryVehiclesYellowController@deleteValidateRubro');

        Route::post('export-hoja-de-vida-activo','ResumeMachineryVehiclesYellowController@exportHojaDeVidaActivo');

        Route::post('export-hoja-de-vida-activo-machinery','ResumeMachineryVehiclesYellowController@exportHojaDeVidaActivoMachinery');


        Route::get('get-resume-machinery-vehicles-yellows', 'ResumeMachineryVehiclesYellowController@all')->name('all');
        
        Route::post('export-resume-machinery-vehicles-yellows', 'ResumeMachineryVehiclesYellowController@export')->name('export');

        Route::get('get-resume-machinery/{id}', 'ResumeMachineryVehiclesYellowController@tireInformation');

        Route::get('get-users-authorized/{asset}/{category}', 'ResumeMachineryVehiclesYellowController@getAllUsers');
            //Envia todas las referencias de llantas
        Route::get('get-tire-reference', 'ResumeMachineryVehiclesYellowController@sendTireReference');

        Route::get('get-categories-asset/{id}', 'CategoryController@getCategoriesAsset');


        Route::resource('documents-assets', 'DocumentsAssetsController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        Route::post('documents-assets/{ma}/{mft}', ['as' => 'DocumentsAssets.store', 'uses' => 'DocumentsAssetsController@store']);

        Route::get('get-documents-assets/{ma}/{mft}', 'DocumentsAssetsController@all')->name('all');
        
        Route::post('export-documents-assets', 'DocumentsAssetsController@export')->name('export');

        Route::get('get-requirement-for-operation/{id}', 'MinorEquipmentFuelController@getRequirementOperation');

        Route::resource('resume-equipment-machineries', 'ResumeEquipmentMachineryController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        //Ruta para eliminar un consumo de equipos menores
        Route::put('delete-minor-equipment-fuel-consumption', 'EquipmentMinorFuelConsumptionController@destroy');

        Route::get('get-resume-equipment-machineries', 'ResumeEquipmentMachineryController@all')->name('all');
        
        Route::post('export-resume-equipment-machineries', 'ResumeEquipmentMachineryController@export')->name('export');
        Route::post('temporary-files', 'ResumeMachineryVehiclesYellowController@prueba');

        Route::resource('resume-equipment-machinery-lecas', 'ResumeEquipmentMachineryLecaController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-resume-equipment-machinery-lecas', 'ResumeEquipmentMachineryLecaController@all')->name('all');
        
        Route::post('export-resume-equipment-machinery-lecas', 'ResumeEquipmentMachineryLecaController@export')->name('export');


        Route::resource('resume-equipment-lecas', 'ResumeEquipmentLecaController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-resume-equipment-lecas', 'ResumeEquipmentLecaController@all')->name('all');
        
        Route::post('export-resume-equipment-lecas', 'ResumeEquipmentLecaController@export')->name('export');

        
        Route::resource('resume-inventory-lecas', 'ResumeInventoryLecaController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-resume-inventory-lecas', 'ResumeInventoryLecaController@all')->name('all');
        
        Route::post('export-resume-inventory-lecas', 'ResumeInventoryLecaController@export')->name('export');
        
        
        Route::resource('resume-inventory-lecas', 'ResumeInventoryLecaController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        Route::get('get-resume-inventory-lecas', 'ResumeInventoryLecaController@all')->name('all');
        
        Route::post('export-resume-inventory-lecas', 'ResumeInventoryLecaController@export')->name('export');

        Route::get('budget-provider', 'ProviderContractController@indexBudget');

        Route::get('get-provider-dependencia', 'ProviderContractController@contractDependencia');

        Route::get('budget-execution-index', 'ProviderContractController@indexExecution');

        Route::resource('provider-contracts', 'ProviderContractController', ['only' => [
            'index', 'store', 'update', 'destroy', 'show'
        ]]);
        Route::get('get-provider-contracts', 'ProviderContractController@all')->name('all');

        Route::get('get-butget-executions-provider', 'ProviderContractController@allExecution');

        Route::post('export-provider-contracts', 'ProviderContractController@export')->name('export');

        Route::get('get-contracts-by-dependence/{id_proceso}', 'RequestNeedController@getContractsByDependence')->name('getContractsByDependence');
        Route::get('item-value-available/{itemId}', 'RequestNeedController@getAvailableValueByItem')->name('getAvailableValueByItem');
        
        Route::put('delete-provider-contracts', 'ProviderContractController@destroy');

        Route::put('new-condition', 'ProviderContractController@newCondition');
        
        Route::resource('documents-provider-contracts', 'DocumentsProviderContractController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        Route::post('documents-provider-contracts/{mpc}', ['as' => 'DocumentsProviderContract.store', 'uses' => 'DocumentsProviderContractController@store']);

        Route::get('get-documents-provider-contracts/{mpc}', 'DocumentsProviderContractController@all')->name('all');
        
        Route::post('export-documents-provider-contracts', 'DocumentsProviderContractController@export')->name('export');

        
        Route::resource('import-parts-provider-cont', 'ImportSparePartsProviderContractController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        Route::post('import-parts-provider-cont/{mpc}', ['as' => 'ImportSparePartsProviderContract.store', 'uses' => 'ImportSparePartsProviderContractController@store']);

        Route::get('get-import-parts-provider-cont/{mpc}', 'ImportSparePartsProviderContractController@all')->name('all');
        
        Route::post('export-import-spare-parts-provider-contracts-edit', 'ImportSparePartsProviderContractController@export')->name('export');


        Route::resource('import-acti-provider-cont', 'ImportActivitiesProviderContractController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        Route::post('import-acti-provider-cont/{mpc}', ['as' => 'ImportActivitiesProviderContract.store', 'uses' => 'ImportActivitiesProviderContractController@store']);

        Route::get('get-import-acti-provider-cont/{mpc}', 'ImportActivitiesProviderContractController@all')->name('all');
        
        Route::post('export-import-activities-provider-contracts-edit', 'ImportActivitiesProviderContractController@export')->name('export');


        // Ruta para la gestion de vehicleFuels
        Route::resource('vehicle-fuels', 'VehicleFuelController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de vehicleFuels
        Route::get('get-vehicle-fuels', 'VehicleFuelController@all')->name('all');

        Route::put('get-vehicle-fuels-delete', 'VehicleFuelController@destroy');

        // Ruta que obtiene datos de un vehiculo segun se ingrese una placa en el formulario de registro
        Route::get('get-vehicle', 'VehicleFuelController@getVehicle');
        
        // Ruta que obtiene datos de un vehiculo segun se ingrese una placa en el formulario de registro
        Route::get('vehicles/{fieldName}', 'VehicleFuelController@getVehicleFieldByPlate');

        // Ruta que obtiene datos de un vehiculo segun se ingrese una placa en el formulario de registro
        Route::get('get-info/{id}', 'VehicleFuelController@getInfo');

        // Ruta para exportar los datos de vehicleFuels
        Route::post('export-vehicle-fuels', 'VehicleFuelController@export')->name('export');

        // Ruta para importar registros a la tabla del ocmponente de gestion de combustible de vehiculos 
        Route::get('index-import-vehicle-fuels', 'VehicleFuelController@indexImport')->name('indexImport');

        // Ruta para importar registros a la tabla del componente de gestion de combustible de vehiculos 
        Route::post('historical-vehicle-fuels', 'VehicleFuelController@historicalRegisters')->name('historicalRegisters');

        // Ruta que muestra index principal del historico de gestion de combustibles 
        Route::get('historical-import-vehicle-fuel', 'VehicleFuelController@indexHistorical')->name('indexImport');

        // Ruta que muestra index principal del historico de gestion de combustibles 
        Route::get('register-all-vehicle-fuel', 'VehicleFuelController@indexRegisterAll')->name('indexRegisterAll');

        // Ruta que muestra index principal del historico de gestion de combustibles 
        Route::get('get-all-vehicles', 'VehicleFuelController@allRegisters');

        // Ruta para exportar los datos del historico de gestion de combustibles
        Route::post('export-historical-vehicle-fuel', 'VehicleFuelController@exportHistorical')->name('exportHistorical');

        // Ruta para consultar registros importados de gestion de combustible de vehiculos
        Route::get('get-historical-vehicle', 'VehicleFuelController@getHistoricalVehicleFuel')->name('getImport');
        
        Route::put('add-documents-by-vehicle-fuels', 'VehicleFuelController@addDocumentByVehicleFuels');

        // Ruta para la gestion de fuelDocuments
        Route::resource('fuel-documents', 'FuelDocumentController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de fuelDocuments
        Route::get('get-fuel-documents', 'FuelDocumentController@all')->name('all');
        // Ruta para exportar los datos de fuelDocuments
        Route::post('export-fuel-documents', 'FuelDocumentController@export')->name('export');
        
        // Ruta para la gestion de budgetAssignations
        Route::resource('budget-assignations', 'BudgetAssignationController', ['only' => [
            'index', 'store', 'update', 'destroy','show'
        ]]);
        // Ruta que obtiene todos los registros de budgetAssignations
        Route::get('get-budget-assignations', 'BudgetAssignationController@all')->name('all');
        // Ruta para exportar los datos de budgetAssignations
        Route::post('export-budgetAssignations', 'BudgetAssignationController@export')->name('export');

        //Guardar novedades del contrato
        Route::put('save-new-contract', 'BudgetAssignationController@saveNewContract');

        Route::put('delete-budget-assignation', 'BudgetAssignationController@destroy');


        // Ruta para la gestion de administrationCostItems
        Route::resource('administration-cost-items', 'AdministrationCostItemController', ['only' => [
            'index', 'store', 'update', 'destroy', 'show'
        ]]);
        // Ruta que obtiene todos los registros de administrationCostItems
        Route::get('get-administration-cost-items', 'AdministrationCostItemController@all')->name('all');
        // Ruta para exportar los datos de administrationCostItems
        Route::post('export-administrationCostItems', 'AdministrationCostItemController@export')->name('export');

        Route::put('delete-cost-item','AdministrationCostItemController@destroy');


        // Ruta para la gestion de butgetExecutions
        Route::resource('butget-executions', 'ButgetExecutionController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de butgetExecutions
        Route::get('get-butget-executions', 'ButgetExecutionController@all')->name('all');
        // Ruta para exportar los datos de butgetExecutions
        Route::post('export-butgetExecutions', 'ButgetExecutionController@export')->name('export');


        //Ruta que obtine todos los rubros
        Route::get('get-heading', 'AdministrationCostItemController@getHeading');

         //Ruta que obtine todos los rubros
         Route::get('get-heading-unity/{activeId}', 'AdministrationCostItemController@getHeadingUnity');

         //Ruta que obtine todos los rubros
         Route::get('get-heading-unity-by-rubro/{rubro_id}', 'AdministrationCostItemController@getHeadingUnityByRubro');

          //Ruta que obtine todos los rubros
          Route::get('get-heading-unity-aseo/{dependencia}', 'AdministrationCostItemController@getHeadingUnityAseo');

          //Ruta que obtiene todos los rubros de una dependencia
          Route::get('get-items-by-dependency/{dependencyId}', 'AdministrationCostItemController@getItemsByDependency');

          //Ruta que obtiene todos los rubros de un objeto de contrato
          Route::get('get-items-by-object-contract/{contractObjectId}', 'AdministrationCostItemController@getItemsByObjectContractId');

        //Ruta que obtine todos los centros de costo
        Route::get('get-center-cost', 'AdministrationCostItemController@getCenterCost');

        //Ruta que obtiene el valor del cdp 
        Route::get('get-value-cdp/{id}', 'ButgetExecutionController@getValueCdp');

        //Ruta que obtiene el valor del contrato
        Route::get('get-value-contract/{id}', 'ButgetExecutionController@getValueContract');

        //Ruta que obtiene el valor actual de saldo disponible 
        Route::get('get-value-avaible/{id}', 'ButgetExecutionController@getValueAvaible');

        //Ruta que obtiene el valor actual de saldo disponible 
        Route::get('get-value-avaible-novelty/{cdp}/{contract}', 'ButgetExecutionController@getValueAvaibleNovelty');

        //Ruta que obtiene el valo-nover actual de saldo disponible a editar
        Route::get('get-value-avaible-edit/{id}', 'ButgetExecutionController@getValueAvaibleEdit');

        //Ruta que obtiene el ultimo porcentaje en la tabla de ejecucion presupuestal 
        Route::get('get-value-percentage/{id}', 'ButgetExecutionController@getValuePercentage');

        //Ruta que obtiene el valor disponible de contrato para rubros 
        Route::get('get-avaible-contract/{id}', 'AdministrationCostItemController@getAvaibleContract');

        //Ruta que obtiene el valor disponible de contrato para rubros 
        Route::get('get-administration-item/{id}', 'ButgetExecutionController@getAdministrationItem');

        //Ruta que obtiene el valor el codigo del rubro 
        Route::get('get-cod-item/{id}', 'AdministrationCostItemController@getCodAdministrationItem');


        //Ruta que obtiene el valor el codigo del centro de costos 
        Route::get('get-cod-center-item/{id}', 'AdministrationCostItemController@getCodCenterAdministrationItem');
            
        // Ruta para la gestion de minorEquipmentFuels
        Route::resource('minor-equipment-fuel', 'MinorEquipmentFuelController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de minorEquipmentFuels
        Route::get('get-minor-equipment-fuel', 'MinorEquipmentFuelController@all')->name('all');
        
        // Ruta que elimina un registro de combustible de equipos menores
        Route::put('delete-minor-equipment-fuel', 'MinorEquipmentFuelController@destroy');

        // Ruta para exportar los datos de minorEquipmentFuels
        Route::post('export-minorEquipmentFuels', 'MinorEquipmentFuelController@export')->name('export');

        // Ruta que obtiene todas las dependencias
        Route::get('get-dependency', 'MinorEquipmentFuelController@getDependency');
        // Ruta que obtiene la suma de todos los registros de consumo por equipo
        Route::get('get-total-consumption/{id}/{tipo}', 'MinorEquipmentFuelController@getTotalConsumption');
        // Ruta que obtiene el saldo final de combustible del ultimo registro
        Route::get('get-final-fuel/{id}/{tipo}', 'MinorEquipmentFuelController@getFinalFuel');
        
        //Envia todos los usuarios de una dependencia
        Route::get('get-users-dependency/{id}', 'MinorEquipmentFuelController@getUserDependency');

        // Ruta para la gestion de equipmentMinorFuelConsumptions
        Route::resource('equipment-minor-fuel-consumptions', 'EquipmentMinorFuelConsumptionController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        

        // Ruta para la gestion de tireQuantitites
        Route::resource('tire-quantitites', 'TireQuantititesController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de tireQuantitites
        Route::get('get-tire-quantitites', 'TireQuantititesController@all')->name('all');
            // Ruta para eliminar llanta
        Route::put('get-tire-quantitites-delete', 'TireQuantititesController@destroy');
        // Ruta para exportar los datos de tireQuantitites
        Route::post('export-tireQuantitites', 'TireQuantititesController@export')->name('export');

        // Ruta que obtiene todas las Dependecias o procesos dela Epa
        Route::get('get-mant-dependencias_id', 'TireQuantititesController@allDependencies');

        // Ruta que obtiene todas las Dependecias o procesos dela Epa
        Route::get('get-mant-vehicles', 'TireQuantititesController@allVehicles');
        
        // Ruta que obtiene todas las Dependecias o procesos dela Epa
        Route::get('get-mant-vehicles-plaques/{id}', 'TireQuantititesController@allVehiclesPlaques');

        //Ruta para obtener la cantidad de llantas
        Route::get('get-tires-actives/{plaque}', 'TireQuantititesController@TiresActives');
        
        Route::get('get-plaque-vehicle','TireQuantititesController@getPlaque');

        Route::get('get-mant-vehicles-all','TireQuantititesController@allVehiclesFilter');
        
        Route::get('datos-cost-item','ContractNewsController@datosCostItem');

        // Ruta para la gestion de tireInformations
        Route::resource('tire-informations', 'TireInformationsController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de tireInformations
        Route::get('get-tire-informations', 'TireInformationsController@all')->name('all');

            // Ruta que elimina de tireInformations
            Route::put('information-delete', 'TireInformationsController@destroy');

        // Ruta para exportar los datos de tireInformations
        Route::post('export-tireInformations/{tipo}', 'TireInformationsController@export')->name('export');

        // Ruta para llamar todas las marcas
        Route::get('get-mant-tire-brand', 'TireInformationsController@allBrands');

        Route::get('get-check-fuel-mileage/{idMachinery}/{fecha}', 'TireInformationsController@checkFuelMileage');

        Route::get('get-check-fuel-mileage-wears/{idMachinery}/{fecha}', 'TireWearsController@checkFuelMileageWears');

        Route::get('get-mant-tire-references','TireInformationsController@allReferences');

        // Obtiene las referencias de una llanta en especifico
        Route::get('tire-references/{tireBrandId}','TireInformationsController@tireReferencesByTireBrand');

        Route::get('get-max-wear/{idTire}','TireInformationsController@MaxWear');

        // Ruta para la gestion de tireWears
        Route::resource('tire-wears', 'TireWearsController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de tireWears
        Route::get('get-tire-wears', 'TireWearsController@all')->name('all');

        Route::post('check-date-tire-wears/{fecha}/{id_tire}/{id_machinery}', 'TireWearsController@checkDateTireWears');

        // Ruta para exportar los datos de tireWears
        Route::post('export-tireWears', 'TireWearsController@export')->name('export');

        // Ruta que obtiene todos los registros de equipmentMinorFuelConsumptions        
            Route::get('get-equipment-minor-fuel-consumptions', 'EquipmentMinorFuelConsumptionController@all')->name('all');
                // Ruta para exportar los datos de equipmentMinorFuelConsumptions
                Route::post('export-equipmentMinorFuelConsumptions', 'EquipmentMinorFuelConsumptionController@export')->name('export');

                Route::post('migration-modal-consumptions', 'EquipmentMinorFuelConsumptionController@MigrateComsuption');

        
                // Ruta que obtiene todas las maquinarias de una dependencia en especifico
                Route::get('get-machinary/{id}', 'EquipmentMinorFuelConsumptionController@getMachinary');
        
                // Ruta que obtiene todas las maquinarias
                Route::get('get-machinary-all', 'EquipmentMinorFuelConsumptionController@getMachinaryAll');
        
                // Ruta que obtiene el saldo disponible de combustible
                Route::get('get-fuel-avaible/{id}', 'EquipmentMinorFuelConsumptionController@getFuelAvaible');
              
                // Ruta para la gestion de documentsMinorEquipments
                Route::resource('documents-minor-equipments', 'DocumentsMinorEquipmentController', ['only' => [
                    'index', 'store', 'update', 'destroy'
                ]]);
                // Ruta que obtiene todos los registros de documents-minor-equipments
                Route::get('get-documents-minor-equipments', 'DocumentsMinorEquipmentController@all')->name('all');
                // Ruta para exportar los datos de documents-minor-equipments
                Route::post('export-documents-minor-equipments', 'DocumentsMinorEquipmentController@export')->name('export');    
        
                       // Ruta para la gestion de tireBrands
                    Route::resource('tire-brands', 'TireBrandController', ['only' => [
                        'index', 'store', 'update', 'destroy'
                    ]]);
                    // Ruta que obtiene todos los registros de tireBrands
                    Route::get('get-tire-brands', 'TireBrandController@all')->name('all');
                    Route::get('get-tire-brands-order-name', 'TireBrandController@getTireBrands');
                    // Ruta para exportar los datos de tireBrands
                    Route::post('export-tireBrands', 'TireBrandController@export')->name('export');
            
                    // Ruta para la gestion de setTires
                    Route::resource('set-tires', 'SetTireController', ['only' => [
                        'index', 'store', 'update', 'destroy'
                    ]]);
                    // Ruta que obtiene todos los registros de setTires
                    Route::get('get-set-tires', 'SetTireController@all')->name('all');
                    // Ruta para exportar los datos de setTires
                    Route::post('export-setTires', 'SetTireController@export')->name('export');
                    // Ruta que obtiene las marcas de las llantas
                    Route::get('get-brands', 'SetTireController@getBrands');
            
                    // Ruta para la gestion de inflationPressures
                    Route::resource('inflation-pressures', 'InflationPressureController', ['only' => [
                        'index', 'store', 'update', 'destroy'
                    ]]);
                    // Ruta que obtiene todos los registros de inflationPressures
                    Route::get('get-inflation-pressures', 'InflationPressureController@all')->name('all');
                    // Ruta para exportar los datos de inflationPressures
                    Route::post('export-inflationPressures', 'InflationPressureController@export')->name('export');
                
                    // Inicio de rutas para la consulta de los registros que tiene relación con los tipos de activos, categorías y autorizaciones
                    Route::get('get-use-type-asset', 'AssetTypeController@getCountAssetType');
                    Route::get('get-use-category', 'CategoryController@getCountCategory');
                    
        
                    // Ruta para la gestion de historyCostItems
                    Route::resource('historyCostItems', 'HistoryCostItemController', ['only' => [
                        'index', 'store', 'update', 'destroy'
                    ]]);
                    // Ruta que obtiene todos los registros de historyCostItems
                    Route::get('get-historyCostItems', 'HistoryCostItemController@all')->name('all');
                    // Ruta para exportar los datos de historyCostItems
                    Route::post('export-historyCostItems', 'HistoryCostItemController@export')->name('export');
        
                    // Ruta para la gestion de historyBudgetAssignations
                    Route::resource('historyBudgetAssignations', 'HistoryBudgetAssignationController', ['only' => [
                        'index', 'store', 'update', 'destroy'
                    ]]);
                    // Ruta que obtiene todos los registros de historyBudgetAssignations
                    Route::get('get-historyBudgetAssignations', 'HistoryBudgetAssignationController@all')->name('all');
                    // Ruta para exportar los datos de historyBudgetAssignations
                    Route::post('export-historyBudgetAssignations', 'HistoryBudgetAssignationController@export')->name('export');
        
        
                    // Ruta para la gestion de historyProviderContracts
                    Route::resource('historyProviderContracts', 'HistoryProviderContractController', ['only' => [
                        'index', 'store', 'update', 'destroy'
                    ]]);
                    // Ruta que obtiene todos los registros de historyProviderContracts
                    Route::get('get-historyProviderContracts', 'HistoryProviderContractController@all')->name('all');
                    // Ruta para exportar los datos de historyProviderContracts
                    Route::post('export-historyProviderContracts', 'HistoryProviderContractController@export')->name('export');
        
        
                    //Rutas indicadores index
                    Route::get('indicators-index', 'IndicatorsController@index');
                    //Ruta indicadores verifica si existen registros
                    Route::post('verify-indicator', 'IndicatorsController@verifyIndicator');
                    //Ruta indicdores esta ruta es para exportar el excel
                    Route::post('create-excel','IndicatorsController@reportExcel');
                    //Ruta indicadores esta ruta retorna el nombre de un activo
                    Route::get('get-active/{id}', 'IndicatorsController@getNameActive');
                    //Ruta obtiene los dependencias
                    Route::get('get-dependency-indicator', 'IndicatorsController@getDependencyIndicator');
                    //Ruta obtiene los activos
                    Route::get('get-type-active-indicator', 'IndicatorsController@getAssetIndicator');
        
    // Ruta para la gestion de oilElementWearConfigurations
        Route::resource('oil-element-wear-configurations', 'OilElementWearConfigurationController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de oilElementWearConfigurations
        Route::get('get-oil-element-wear-configurations', 'OilElementWearConfigurationController@all')->name('all');
        // Ruta para exportar los datos de oilElementWearConfigurations
        Route::post('export-oil-element-wear-configurations', 'OilElementWearConfigurationController@export')->name('export');

        // Ruta para la gestion de oil
        Route::resource('oil', 'OilController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);


        // Eliminar aceite
        Route::put('oil-delete', 'OilController@destroy');

        // Ruta que obtiene todos los registros de oil
        Route::get('get-oil', 'OilController@all')->name('all');
        // Ruta para exportar los datos de oil
        Route::post('export-oil', 'OilController@export')->name('export');
        // Ruta para exportar los datos de oil
        Route::get('get-element-wear/{param}', 'OilController@getElementWear')->name('export');

        Route::get('group-configuration-oil/{id}', 'OilController@groupConfigurationOil');


        Route::get('get-element/{id}', 'OilController@getElement')->name('export');

        // Ruta para la gestion de oilDocuments
        Route::resource('oil-documents', 'OilDocumentController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de oilDocuments
        Route::get('get-oil-documents', 'OilDocumentController@all')->name('all');


        // Ruta para exportar los datos de oilDocuments
        Route::post('export-oil-documents', 'OilDocumentController@export')->name('export');


        Route::resource('data-analytics', 'DataAnalyticsController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);
        //consula la placa
        Route::get('get-vehicle-fleet', 'DataAnalyticsController@getVehicleFleet');

        // Obtiene todos los nombres de los activos segun su tipo de combustible
        Route::get('assets/{fuelType}', 'DataAnalyticsController@getAssetsNameByFuelType');
        // Obtiene todos los nombres de los activos segun su tipo de combustible
        Route::get('assetsA/{fuelType}/{idAssetProcess}', 'DataAnalyticsController@getAssetsNameByFuelType2');


        // Obtiene todas las placas segun su tipo de indicador
        Route::get('plaque/{indicatorType}', 'DataAnalyticsController@getAssetsNameByFuelType2');

        // Obtiene todos los tipos de carroceria segun el nombre del activo
        Route::get('body-type/{nameVehicleMachinery}', 'DataAnalyticsController@getBodyTypeByNameVehicleMachinery');

        // Obtiene todos las placas cuando el tipo de indicador sea rendimiento de combustible
        Route::get('fuel-effiency/plaques/{assestProcessId}/{fuelType}/{assetName}/{bodyType}', 'DataAnalyticsController@getPlaquesForFuelEfficiency');

        // Obtiene todos los nombres de los activos segun su tipo de combustible
        //  Route::get('assetsB/{fuelType}', 'DataAnalyticsController@getAssetsNameByFuelType3');


        Route::get('assetsB/{depencenceId}/{status}', 'DataAnalyticsController@getAssetsNameByDependenceAndStatus');

        //consulta la lista de vehiculos
        Route::get('get-plaque/{informationAsset}', 'DataAnalyticsController@getPlateByAsset');
        //consulta los tipod de activos 
        Route::get('get-asset/{id}', 'DataAnalyticsController@getAsset');
        //consulta todos  los proveedores.
        Route::get('get-provider', 'DataAnalyticsController@getProvider');
        //consulta los contratos y resibe el parametro con el id del proveedor.
        Route::get('get-contract/{id}', 'DataAnalyticsController@getContract');
        // Ruta que obtiene todos los registros de oilElementWearConfigurations
        Route::get('get-data-analytics', 'DataAnalyticsController@all')->name('all');
        // Ruta para exportar los datos de oilElementWearConfigurations
        Route::post('export-data-analytics', 'DataAnalyticsController@export')->name('export');
        Route::post('verify-data-analytics','DataAnalyticsController@verifyDataAnalytics');



        // Ruta para la gestion de fuelHistories
        Route::resource('fuel-histories', 'FuelHistoryController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de fuelHistories
        Route::get('get-fuel-histories', 'FuelHistoryController@all')->name('all');

        // Ruta para la gestion de fuelEquipmentHistories
        Route::resource('fuel-equipment-histories', 'FuelEquipmentHistoryController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de fuelEquipmentHistories
        Route::get('get-fuel-equipment-histories', 'FuelEquipmentHistoryController@all')->name('all');

        // Ruta para exportar los datos de fuelEquipmentHistories
        Route::post('export-fuelEquipmentHistories', 'FuelEquipmentHistoryController@export')->name('export');



        // Ruta para la gestion de fuelConsumptionHistoryMinors
        Route::resource('fuel-consumption-history-minors', 'FuelConsumptionHistoryMinorsController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de fuelConsumptionHistoryMinors
        Route::get('get-fuel-consumption-history-minors', 'FuelConsumptionHistoryMinorsController@all')->name('all');
        // Ruta para exportar los datos de fuelConsumptionHistoryMinors
        Route::post('export-fuel-consumption-history-minors', 'FuelConsumptionHistoryMinorsController@export')->name('export');

        // Ruta para la gestion de tireGestionHistories
        Route::resource('tire-gestion-histories', 'TireGestionHistoryController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de tireGestionHistories
        Route::get('get-tire-gestion-histories', 'TireGestionHistoryController@all')->name('all');
        // Ruta para exportar los datos de tireGestionHistories
        Route::post('export-tireGestionHistories', 'TireGestionHistoryController@export')->name('export');

        // Ruta para la gestion de oilHistories
        Route::resource('oil-histories', 'OilHistoryController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de oilHistories
        Route::get('get-oil-histories', 'OilHistoryController@all')->name('all');
        // Ruta para exportar los datos de oilHistories
        Route::post('export-oilHistories', 'OilHistoryController@export')->name('export');



        // Ruta para la gestion de tireInformationHistories
        Route::resource('tire-information-histories', 'tireInformationHistoryController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de tireInformationHistories
        Route::get('get-tire-information-histories', 'tireInformationHistoryController@all')->name('all');
        // Ruta para exportar los datos de tireInformationHistories
        Route::post('export-tireInformationHistories', 'tireInformationHistoryController@export')->name('export');

        Route::get('get-change-status-history/{id}','tireInformationHistoryController@changeStatus');


        // Ruta para la gestion de tireWearHistories
        Route::resource('tire-wear-histories', 'TireWearHistoryController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de tireWearHistories
        Route::get('get-tire-wear-histories', 'TireWearHistoryController@all')->name('all');
        // Ruta para exportar los datos de tireWearHistories
        Route::post('export-tireWearHistories', 'TireWearHistoryController@export')->name('export');

        // Ruta que obtiene todos los registros de tireWearHistories
        Route::get('get-change-status-history-wear/{id}', 'TireWearHistoryController@changeStatus');

        // // Ruta para la gestion de newContracts novedades de contratos
        // Route::resource('new-contracts', 'NewContractsController', ['only' => [
        // 'index', 'store', 'update', 'destroy'
        // ]]);
        // // Ruta que obtiene todos los registros de newContracts
        // Route::get('get-new-contracts', 'NewContractsController@all')->name('all');
        // // Ruta para exportar los datos de newContracts
        // Route::post('export-newContracts', 'NewContractsController@export')->name('export');


        // Ruta para la gestion de contractNews
        Route::resource('contract-news', 'ContractNewsController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene la ultima fecha de la suspension
        Route::get('get-date-last-suspension/{id}', 'ContractNewsController@getDateLastSuspension');
        // Ruta que obtiene todos los registros de contractNews
        Route::get('get-contract-news', 'ContractNewsController@all')->name('all');
        // Ruta para exportar los datos de contractNews
        Route::post('export-contractNews', 'ContractNewsController@export')->name('export');
         // Ruta para exportar los datos de contractNews
         Route::delete('elimina/{mpc}/{id}', 'ContractNewsController@elimina');


        // Ruta para la gestion de requestNeeds
        Route::resource('request-needs', 'RequestNeedController', ['only' => [
        'index', 'store', 'update', 'destroy'
        ]]);

        // Envia una solicitud de stock a tramite
        Route::get('request-need-stock/{requestNeedId}', 'RequestNeedController@sendStockRequestToProcessing')->name('all');

        // Envia una solicitud de stock automaticamente al almacen aseo
        Route::get('request-need-stock-aseo/{requestNeedId}', 'RequestNeedController@sendRequestToWarehouseCleanliness');

        // Envia una solicitud de almacen a tramite
        Route::get('request-need-warehouse/{requestNeedId}', 'RequestNeedController@sendWareHouseRequestToProcessing')->name('all');

        // Ruta que obtiene todos los registros de requestNeeds
        Route::get('get-request-needs', 'RequestNeedController@all')->name('all');

        Route::get('request-need-types-request/{dependence}', 'RequestNeedController@getTypesRequest')->name('all');
        // Ruta para exportar los datos de requestNeeds
        Route::post('export-request-needs', 'RequestNeedController@export')->name('export');

        Route::get('get-tire-wears', 'TireWearsController@all')->name('all');

        Route::get('get-all-actives-by-type/{type}/{dependencia}', 'RequestNeedController@allActivesByType')->name('allActivesByType');

        Route::get('get-all-contracts-by-rubros/{id}/{secondRubro?}', 'RequestNeedController@allContracts')->name('allContracts');
        Route::get('get-all-contracts-by-rubros-inventario/{id}', 'RequestNeedController@allContractsInventario')->name('allContractsInventario');

        Route::get('get-rubros-by-contrato/{id}', 'RequestNeedController@getRubrosByContrato')->name('getRubrosByContrato');


        Route::get('get-aseo-contracts-by-rubros/{id}', 'RequestNeedController@aseoContracts')->name('allContracts');

        Route::get('get-aseo-contracts', 'RequestNeedController@getCleaningManagementAllContracts');

        Route::get('get-contracts-by-activo/{id}', 'RequestNeedController@getContractsByActive');




        // Obtiene todos los contratos relacionados a la dependencia
        Route::get('get-contracts-by-dependency/{dependencyId}', 'RequestNeedController@getContractsByDependency');

        Route::get('get-dependencies/{id}', 'RequestNeedController@getDependencias')->name('getDependencias');

        //busca actividades y repuestos
        Route::get('get-all-descriptions-by-need/{tipo}/{contrato}', 'RequestNeedController@allDescriptions')->name('allDescriptions');

        Route::get('get-all-descriptions-stock/{winery}', 'RequestNeedController@getAllDescriptionsToStock');

        Route::get('physical-quantity-stock/{descriptionId}', 'RequestNeedController@getPhysicalQuantityStockByDescription');

        Route::get('available-quantity-stock/{descriptionId}', 'RequestNeedController@getAvailableQuantityStockByDescription');

        Route::get('send-request-need/{estado}', 'RequestNeedController@sendRequest')->name('sendRequest');



        // Ruta para exportar los datos de requestNeedOrders
        Route::put('finish-request', 'RequestNeedOrdersController@finishRequest');

      


        Route::get('get-all-descriptions-by-need-request/{id}', 'RequestNeedController@allDescriptionsByrequest')->name('allDescriptionsByrequest');
        Route::get('get-all-descriptions-by-need-request-id/{id}', 'RequestNeedController@allDescriptionsByrequestId')->name('allDescriptionsByrequestId');

        Route::put('send-request-need-order', 'RequestNeedOrdersController@sendRequestOrder')->name('sendRequestOrder');

        Route::get('get-providers_internals', 'RequestNeedController@allProviderInternals')->name('allProviderInternals');

        Route::put('finish-state-provider', 'RequestNeedOrdersController@finishStateProvider')->name('finishStateProvider');

        //entrada
        Route::put('send-entrada', 'RequestNeedOrdersController@sendEntrada')->name('sendEntrada');

        Route::put('send-numero-factura', 'RequestNeedOrdersController@sendNumeroFactura')->name('sendNumeroFactura');

        Route::put('change-state-request', 'RequestNeedController@changeStateRequest')->name('changeStateRequest');

        Route::put('send-salida', 'RequestNeedOrdersController@sendSalida')->name('sendSalida');

        Route::post('get-formato-identificacion-necesidades', 'RequestNeedController@getFormatoIdentificacionNecesidades')->name('getFormatoIdentificacionNecesidades');

        // Ruta para la gestion de requestAnnotations
        Route::resource('request-annotations', 'RequestAnnotationController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de requestAnnotations
        Route::get('get-request-annotations', 'RequestAnnotationController@all')->name('all');
        // Ruta para exportar los datos de requestAnnotations
        Route::post('export-requestAnnotations', 'RequestAnnotationController@export')->name('export');

                
        // Ruta para la gestion de documentOrders
        Route::resource('document-orders', 'DocumentOrderController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de documentOrders
        Route::get('get-document-orders', 'DocumentOrderController@all')->name('all');
        // Ruta para exportar los datos de documentOrders
        Route::post('export-documentOrders', 'DocumentOrderController@export')->name('export');
        // Obtiene el kilometraje u horometro actual del activo
        Route::get('active-mileage-or-current-hourmeter/{activeId}', 'RequestNeedOrdersController@getActiveMileageOrCurrentHourmeterByActive');

        // 

        // Exporta el pdf del certificado VIG-GR-R-026
        Route::get('vig-gr-r-026/{requestId}', 'RequestNeedOrdersController@exportCertificateVigGrR026');

        // Ruta para la gestion de stocks
        Route::resource('stock', 'StockController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de stocks
        Route::get('get-stock', 'StockController@all')->name('all');
        // Ruta para exportar los datos de stocks
        Route::post('export-stocks', 'StockController@export')->name('export');

        // Ruta para la gestion de assetManagements
        Route::resource('asset-managements', 'AssetManagementController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        // Ruta que obtiene todos los registros de assetManagements
        Route::get('get-asset-managements', 'AssetManagementController@all')->name('all');

        Route::get('asset-managements/{id}', 'AssetManagementController@show')->name('show');

         // Ruta para obtener la información de un mantenimiento por el id
        Route::get('get-asset-show/{id}', 'AssetManagementControllerr@showAssetManagement');

        // Ruta para exportar los datos de assetManagements
        Route::post('export-assetManagements', 'AssetManagementController@export')->name('export');

        
    });

        /*
        * Rutas para el proveedor externo de necesidades
        * 
        */

       // Ruta para exportar los datos de requestNeedOrders
         Route::get('generate-gr-r/{id}', 'RequestNeedOrdersController@fromatGr');

        Route::get('get-contracts-by-external-provider/{providerId}', 'ProviderContractController@getContractsByExternalProvider');
        Route::get('get-contracts', 'ProviderContractController@getContracts');
        
        Route::resource('request-need-orders', 'RequestNeedOrdersController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);

        Route::get('request-information-external-provider/{requestId}', 'RequestNeedOrdersController@getRequestInformationExternalProvider');

        // Ruta que obtiene todos los registros de requestNeedOrders
        Route::get('get-request-need-orders/{needId}', 'RequestNeedOrdersController@all')->name('all');

        // Obtiene el kilometraje u horometro actual para el formulario de finalizar solicitud de un proveedor externo
        Route::get('current-mileage-hourmeter/{requestId}', 'RequestNeedOrdersController@getCurrentMileageHourmeter');

        Route::get('vig-gr-r-026-to-provider/{id}', 'RequestNeedController@getFormatoIdentificacionNecesidadesMail')->name('getFormatoIdentificacionNecesidadesMail');
        
        Route::get('vig-gr-r-026-to-provider-order/{id}', 'RequestNeedController@getFormatoIdentificacionNecesidadesOrderMail')->name('getFormatoIdentificacionNecesidadesOrderMail');

        Route::post('request-need-orders/{needId}', ['as' => 'request-need-orders.store', 'uses' => 'RequestNeedOrdersController@store']);
        // Obtiene el ultimo registro de gestion de combustible por fecha
        Route::get('fual-management-by-date/{requestId}/{date}', 'RequestNeedOrdersController@getLatestFualManagementByDate');

        Route::get('vig-gr-r-026-to-provider/{id}', 'RequestNeedController@getFormatoIdentificacionNecesidadesMail')->name('getFormatoIdentificacionNecesidadesMail');
        
        Route::get('vig-gr-r-026-to-provider-order/{id}', 'RequestNeedController@getFormatoIdentificacionNecesidadesOrderMail')->name('getFormatoIdentificacionNecesidadesOrderMail');

        // Ruta para exportar los datos de requestNeedOrders
        Route::post('export-request-need-orders', 'RequestNeedOrdersController@export')->name('export');

        // Almacena el registro de la solicitud del proveedor externo
        Route::post('external-provider-request', 'RequestNeedOrdersController@saveExternalProviderRequest');

        // Exporta la solicitud en un formato xlsx para el provedor externo
        Route::post('export-request-need-external-provider', 'RequestNeedController@getFormatoIdentificacionNecesidades');

        
        // Ruta para la gestion de addition-spare-part-activities
        Route::resource('addition-spare-part-activities', 'AdditionSparePartActivityController', ['only' => [
            'index', 'store', 'update', 'destroy'
        ]]);
        // Ruta que obtiene todos los registros de addition-spare-part-activities
        Route::get('get-addition-spare-part-activities', 'AdditionSparePartActivityController@all')->name('all');
        // Ruta para exportar los datos de addition-spare-part-activities
        Route::post('export-addition-spare-part-activities', 'AdditionSparePartActivityController@export')->name('export');

        Route::put('addition-spare-part-activities-process', 'AdditionSparePartActivityController@createProcess');
        Route::match(['post', 'put'],'request-addition-process', 'AdditionSparePartActivityController@processAddition');


    });

    Route::group(['prefix' => 'maintenance'], function () {
        Route::get('watch-archives', 'ResumeMachineryVehiclesYellowController@watchDocument');
    });



