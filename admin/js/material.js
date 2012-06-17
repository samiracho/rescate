// formulario para editar materiales
Ext.define('RESCATE.form.editarMaterialIntervencion', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarMaterialIntervencion',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: ('Material-Intervention'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        me.items = [
        {
            xtype: 'form',
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '100%'
            },
            bodyPadding: 10,
            title: '',
            items: [
            {
                xtype: 'textfield',
                name: 'material_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'materialintervencion_intervencion_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'materialintervencion_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// formulario para editar materiales
Ext.define('RESCATE.form.editarMaterial', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarMaterial',
    height: 400,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Material'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        var categoriasStore = Ext.create('widget.store.CategoriaMaterial', {
        });
        me.items = [
        {
            xtype: 'form',
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '100%'
            },
            bodyPadding: 10,
            title: '',
            items: [
            {
                xtype: 'textfield',
                name: 'material_id',
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'material_nombre',
                fieldLabel: t('Name') + '*',
                allowBlank: false
            }, {
                xtype: 'form.ComboBox',
                displayField: 'nombre',
                valueField: 'id',
                name: 'material_padre_id',
                store: categoriasStore,
                fieldLabel: t('Category') + '*',
                value: '',
                emptyText: t('Select category'),
                allowBlank: false
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% -90',
                name: 'material_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        // Datoscargados es un evento que se dispara cuando el record ya ha sido cargado en los campos del formulario
        me.addListener('datoscargados', function (component)
        {
            categoriasStore.load();
        });
        me.callParent(arguments);
    }
});
Ext.define('RESCATE.TreePanel.Material', {
    alias: 'widget.TreePanel.Material',
    extend: 'RESCATE.TreePanel',
    minHeight: 230,
    height: 230,
    minSize: 150,
    autoScroll: true,
    title: t('List'),
    // para mostrar solo los iconos y no los textos en buscar y refrescar
    soloIconos: true,
    // para mostrar solo los iconos y no los textos en agregar editar y refrescar
    soloIconosEdicion: true,
    // si es false ocultamos los iconos de agregar,editar,eliminar
    botonAgregar: CONFIG.perms.administrar_materiales,
    botonEditar: CONFIG.perms.administrar_materiales,
    botonBorrar: CONFIG.perms.administrar_materiales,
    // itemId del campo donde se guarda el valor de la relación
    idRelacion: 'campoIdIntervencion',
    nombreRelacion: '',
    //formulario de edicion
    formulario: 'editarMaterial',
    initComponent: function ()
    {
        var me = this;
        me.store = Ext.create('widget.store.Material', {
        });
        me.callParent(arguments);
    }
});
// grid con la lista editable de materiales
Ext.define('RESCATE.grid.Material', {
    alias: 'widget.grid.Material',
    extend: 'RESCATE.grid.Drop',
    title: t('Materials'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: true,
    botonBorrar: true,
    formulario: 'editarMaterialIntervencion',
    mensajeAgregar: t('Add Tech'),
    deleteParam1: 'material_id',
    deleteParam2: 'materialintervencion_intervencion_id',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'material_id',
        sortable: true,
		hidden:true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'material_nombre',
        sortable: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'material_detalles',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.MaterialIntervencion', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de materiales y el grid editable de materiales
Ext.define('RESCATE.panel.Material', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.Material',
    title: '',
    idRelacion: 'campoIdIntervencion',
    gridEditXtype: 'grid.Material',
    gridEditItemId: 'gridMaterial',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'materialGridDDGroup',
    gridListXtype: 'TreePanel.Material',
    gridListItemId: 'gridMaterialLista'
});