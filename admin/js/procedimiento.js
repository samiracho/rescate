// formulario para editar procedimientos
Ext.define('RESCATE.form.editarProcedimientoIntervencion', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarProcedimientoIntervencion',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: ('Procedure-Intervention'),
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
                name: 'procedimiento_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'procedimientointervencion_intervencion_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'procedimientointervencion_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// formulario para editar procedimientos
Ext.define('RESCATE.form.editarProcedimiento', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarProcedimiento',
    height: 400,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Procedimiento'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        var categoriasStore = Ext.create('widget.store.CategoriaProcedimiento', {
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
                name: 'procedimiento_id',
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'procedimiento_nombre',
                fieldLabel: t('Name') + '*',
                allowBlank: false
            }, {
                xtype: 'form.ComboBox',
                displayField: 'nombre',
                valueField: 'id',
                name: 'procedimiento_padre_id',
                store: categoriasStore,
                fieldLabel: t('Category') + '*',
                value: '',
                emptyText: t('Select category'),
                allowBlank: false
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% -90',
                name: 'procedimiento_detalles'
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
Ext.define('RESCATE.TreePanel.Procedimiento', {
    alias: 'widget.TreePanel.Procedimiento',
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
    botonAgregar: CONFIG.perms.administrar_procedimientos,
    botonEditar: CONFIG.perms.administrar_procedimientos,
    botonBorrar: CONFIG.perms.administrar_procedimientos,
    // itemId del campo donde se guarda el valor de la relación
    idRelacion: 'campoIdIntervencion',
    nombreRelacion: '',
    //formulario de edicion
    formulario: 'editarProcedimiento',
    initComponent: function ()
    {
        var me = this;
        me.store = Ext.create('widget.store.Procedimiento', {
        });
        me.callParent(arguments);
    }
});
// grid con la lista editable de procedimientos
Ext.define('RESCATE.grid.Procedimiento', {
    alias: 'widget.grid.Procedimiento',
    extend: 'RESCATE.grid.Drop',
    title: t('Procedimientos'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: true,
    botonBorrar: true,
    formulario: 'editarProcedimientoIntervencion',
    mensajeAgregar: t('Add Procedure'),
    deleteParam1: 'procedimiento_id',
    deleteParam2: 'procedimientointervencion_intervencion_id',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'procedimiento_id',
        sortable: true,
		hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'procedimiento_nombre',
        sortable: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'procedimiento_detalles',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.ProcedimientoIntervencion', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de procedimientos y el grid editable de procedimientos
Ext.define('RESCATE.panel.Procedimiento', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.Procedimiento',
    title: '',
    idRelacion: 'campoIdIntervencion',
    gridEditXtype: 'grid.Procedimiento',
    gridEditItemId: 'gridProcedimiento',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'procedimientoGridDDGroup',
    gridListXtype: 'TreePanel.Procedimiento',
    gridListItemId: 'gridProcedimientoLista'
});