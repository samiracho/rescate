// formulario para editar técnicas
Ext.define('RESCATE.form.editarMetodoObra', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarMetodoObra',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Professional-Technnic'),
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
                name: 'metodo_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'metodoobra_obra_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'metodoobra_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// formulario para editar técnicas
Ext.define('RESCATE.form.editarMetodo', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarMetodo',
    height: 400,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: 'Technnic',
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        var metodosStore = Ext.create('widget.store.CategoriaMetodo', {
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
                name: 'metodo_id',
                hidden: true
            }, {
                xtype: 'textfield',
                fieldLabel: t('Name') + '*',
                name: 'metodo_nombre',
                allowBlank: false
            }, {
                xtype: 'form.ComboBox',
                displayField: 'nombre',
                valueField: 'id',
                name: 'metodo_padre_id',
                store: metodosStore,
                fieldLabel: t('Category') + '*',
                value: '',
                emptyText: t('Select category'),
                allowBlank: false
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'metodo_detalles',
                margin: '0 0 50 0'
            }],
            dockedItems: me.barraBotones()
        }];
        // Datoscargados es un evento que se dispara cuando el record ya ha sido cargado en los campos del formulario
        me.addListener('datoscargados', function (component)
        {
            metodosStore.load();
        });
        me.callParent(arguments);
    }
});
Ext.define('RESCATE.TreePanel.Metodo', {
    alias: 'widget.TreePanel.Metodo',
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
    botonAgregar: CONFIG.perms.administrar_metodos,
    botonEditar: CONFIG.perms.administrar_metodos,
    botonBorrar: CONFIG.perms.administrar_metodos,
    // itemId del campo donde se guarda el valor de la relación
    idRelacion: 'campoIdObra',
    nombreRelacion: '',
    //formulario de edicion
    formulario: 'editarMetodo',
    initComponent: function ()
    {
        var me = this;
        me.store = Ext.create('widget.store.Metodo', {
        });
        me.callParent(arguments);
    }
});
// grid con la lista editable de técnicas
Ext.define('RESCATE.grid.Metodo', {
    alias: 'widget.grid.Metodo',
    extend: 'RESCATE.grid.Drop',
    title: t('Technnics'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: true,
    botonBorrar: true,
    formulario: 'editarMetodoObra',
    mensajeAgregar: t('Add Technnic'),
    deleteParam1: 'metodo_id',
    deleteParam2: 'metodoobra_obra_id',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'metodo_id',
        sortable: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'metodo_nombre',
        sortable: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'metodo_detalles',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.MetodoObra', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de técnicas y el grid editable de técnicas
Ext.define('RESCATE.panel.Metodo', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.Metodo',
    title: '',
    idRelacion: 'campoIdObra',
    gridEditXtype: 'grid.Metodo',
    gridEditItemId: 'gridMetodo',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'metodoGridDDGroup',
    gridListXtype: 'TreePanel.Metodo',
    gridListItemId: 'gridMetodoLista'
});