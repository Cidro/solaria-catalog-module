<div>
    <ul class="nav nav-tabs" role="tablist">
        <?php if(Auth::user()->can('module_catalog_manage_catalog', null)): ?>
            <li role="presentation" class="<?=$active=='products' ? 'active' : '';?>">
                <a href="<?=url('backend/modules/catalog/products');?>"><span class="glyphicon glyphicon-list-alt"></span> Productos</a>
            </li>
        <?php endif; ?>
        <?php if(Auth::user()->can('module_catalog_manage_catalog', null)): ?>
            <li role="presentation" class="<?=$active=='categories' ? 'active' : '';?>">
                <a href="<?=url('backend/modules/catalog/categories');?>"><span class="glyphicon glyphicon-list-alt"></span> Categorías</a>
            </li>
        <?php endif; ?>
        <?php if(Auth::user()->can('module_catalog_manage_catalog', null)): ?>
            <li role="presentation" class="<?=$active=='attributes' ? 'active' : '';?>">
                <a href="<?=url('backend/modules/catalog/attributes');?>"><span class="glyphicon glyphicon-list-alt"></span> Atributos</a>
            </li>
        <?php endif; ?>
        <?php if(Auth::user()->can('module_catalog_manage_catalog', null)): ?>
            <li role="presentation" class="<?=$active=='packages' ? 'active' : '';?>">
                <a href="<?=url('backend/modules/catalog/packages');?>"><span class="glyphicon glyphicon-list-alt"></span> Paquetes</a>
            </li>
        <?php endif; ?>
        <?php if(Auth::user()->can('module_catalog_manage_catalog', null)): ?>
            <li role="presentation" class="<?=$active=='locations' ? 'active' : '';?>">
                <a href="<?=url('backend/modules/catalog/locations');?>"><span class="glyphicon glyphicon-list-alt"></span> Localidades</a>
            </li>
        <?php endif; ?>
        <?php if(Auth::user()->can('module_catalog_manage_catalog', null)): ?>
            <li role="presentation" class="<?=$active=='taxes' ? 'active' : '';?>">
                <a href="<?=url('backend/modules/catalog/taxes');?>"><span class="glyphicon glyphicon-list-alt"></span> Impuestos</a>
            </li>
        <?php endif; ?>
        <?php if(Auth::user()->can('module_catalog_manage_catalog', null)): ?>
            <li role="presentation" class="<?=$active=='currencies' ? 'active' : '';?>">
                <a href="<?=url('backend/modules/catalog/currencies');?>"><span class="glyphicon glyphicon-list-alt"></span> Monedas</a>
            </li>
        <?php endif; ?>
        <?php if(Auth::user()->can('module_catalog_manage_catalog', null)): ?>
            <li role="presentation" class="<?=$active=='layouts' ? 'active' : '';?>">
                <a href="<?=url('backend/modules/catalog/layouts');?>"><span class="glyphicon glyphicon-list-alt"></span> Plantillas</a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="tab-content tab-content-module-catalog">
        <?=$content;?>
    </div>
</div>