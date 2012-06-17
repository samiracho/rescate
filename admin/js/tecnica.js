// formulario para editar técnicas
Ext.define('RESCATE.form.editarTecnicaProfesional', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarTecnicaProfesional',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: 'Técnica-Profesional',
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
                name: 'tecnica_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'tecnicaprofesional_profesional_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'tecnicaprofesional_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// formulario para editar técnicas
Ext.define('RESCATE.form.editarTecnica', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarTecnica',
    height: 400,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: 'Técnica',
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
                name: 'tecnica_id',
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'tecnica_nombre',
                fieldLabel: t('Name') + '*',
                allowBlank: false
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% -47',
                name: 'tecnica_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// grid con la lista de técnicas
Ext.define('RESCATE.grid.TecnicaLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.grid.TecnicaLista',
    title: t('List'),
    soloIconos: true,
    soloIconosEdicion: true,
    botonAgregar: CONFIG.perms.administrar_tecnicas,
    botonEditar: CONFIG.perms.administrar_tecnicas,
    botonBorrar: CONFIG.perms.administrar_tecnicas,
    formulario: 'editarTecnica',
    mensajeAgregar: t('Add Tech'),
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'tecnica_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'tecnica_nombre',
        sortable: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'tecnica_detalles',
        sortable: true
    }],
    initComponent: function ()
    {
		this.cargarStore();
        this.callParent(arguments);
    },
	cargarStore: function()
	{
		this.store = Ext.create('widget.store.Tecnica', {});
	}
});

// grid con la lista de técnicas en el menúprincipal
Ext.define('RESCATE.panel.TecnicaLista', {
    extend: 'RESCATE.grid.TecnicaLista',
    soloIconos: false,
    soloIconosEdicion: false,
	cargarStore: function()
	{
		this.store = Ext.create('widget.store.Tecnica', {});
        this.store.getProxy().extraParams.norel = 1;
	}
});

// grid con la lista editable de técnicas
Ext.define('RESCATE.grid.Tecnica', {
    alias: 'widget.grid.Tecnica',
    extend: 'RESCATE.grid.Drop',
    title: t('Technnics'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: true,
    botonBorrar: true,
    formulario: 'editarTecnicaProfesional',
    mensajeAgregar: t('Add Tech'),
    deleteParam1: 'tecnica_id',
    deleteParam2: 'tecnicaprofesional_profesional_id',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'tecnica_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        width: 180,
        dataIndex: 'tecnica_nombre',
        sortable: true
    }, {
        text: t('Details'),
        dataIndex: 'tecnicaprofesional_detalles',
        sortable: true,
        flex: 1
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.TecnicaProfesional', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de técnicas y el grid editable de técnicas
Ext.define('RESCATE.panel.Tecnica', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.Tecnica',
    title: t('Technnics'),
    idRelacion: 'campoIdProfesional',
    gridEditXtype: 'grid.Tecnica',
    gridEditItemId: 'gridTecnica',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'tecnicaGridDDGroup',
    gridListXtype: 'grid.TecnicaLista',
    gridListItemId: 'gridTecnicaLista'
});