// formulario para editar tipo documento- profesional
Ext.define('RESCATE.form.editarTipoDocumento', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarTipoDocumento',
    height: 320,
    width: 550,
    title: t('Professional-Document Type'),
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
                name: 'tipo_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'tipodocumento_documento_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'tipodocumento_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// formulario para editar tipos
Ext.define('RESCATE.form.editarTipo', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarTipo',
    height: 400,
    width: 550,
    minHeight: 400,
    minWidth: 550,
    title: t('Document Type'),
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
                name: 'tipo_id',
                hidden: true
            }, {
                xtype: 'textfield',
                fieldLabel: t('Name') + '*',
                name: 'tipo_nombre',
                allowBlank: false
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                name: 'tipo_detalles',
                anchor: '100% 100%',
                margin: '0 0 50 0'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// grid con la lista de tipos de documentos
Ext.define('RESCATE.grid.TipoLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.grid.TipoLista',
    title: t('List'),
    soloIconos: true,
    soloIconosEdicion: true,
    botonAgregar: CONFIG.perms.administrar_tipos,
    botonEditar: CONFIG.perms.administrar_tipos,
    botonBorrar: CONFIG.perms.administrar_tipos,
    formulario: 'editarTipo',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'tipo_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'tipo_nombre',
        sortable: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'tipo_detalles',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Tipo', {
        });
        this.callParent(arguments);
    }
});
// grid con la lista de tipos de documetos en el menú principal
Ext.define('RESCATE.panel.TipoLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.panel.TipoLista',
    soloIconos: false,
    soloIconosEdicion: false,
    botonAgregar: CONFIG.perms.administrar_tipos,
    botonEditar: CONFIG.perms.administrar_tipos,
    botonBorrar: CONFIG.perms.administrar_tipos,
    formulario: 'editarTipo',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'tipo_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        width: 200,
        dataIndex: 'tipo_nombre',
        sortable: true
    }, {
        text: t('Details'),
        flex: 1,
        dataIndex: 'tipo_detalles',
        sortable: true,
        hidden: false
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Tipo', {
        });
        this.store.getProxy().extraParams.norel = 1;
        this.callParent(arguments);
    }
});
// grid con la lista editable de tipos de documentos
Ext.define('RESCATE.grid.Tipo', {
    alias: 'widget.grid.Tipo',
    extend: 'RESCATE.grid.Drop',
    title: t('Document Type'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: true,
    botonBorrar: true,
    formulario: 'editarTipoDocumento',
    mensajeAgregar: t('Add Document Type'),
    deleteParam1: 'tipo_id',
    deleteParam2: 'tipodocumento_documento_id',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'tipo_id',
        sortable: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'tipo_nombre',
        sortable: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'tipo_detalles',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.TipoDocumento', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de técnicas y el grid editable de técnicas
Ext.define('RESCATE.panel.Tipo', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.Tipo',
    title: '',
    idRelacion: 'campoIdDocumento',
    gridEditXtype: 'grid.Tipo',
    gridEditItemId: 'gridTipo',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'tipoGridDDGroup',
    gridListXtype: 'grid.TipoLista',
    gridListItemId: 'gridTipoLista'
});