// formulario para editar técnicas
Ext.define('RESCATE.form.editarEquipamientoProfesional', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarEquipamientoProfesional',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Equipping Details'),
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
                name: 'equipamiento_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'equipamientoprofesional_profesional_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'equipamientoprofesional_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// formulario para editar técnicas
Ext.define('RESCATE.form.editarEquipamiento', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarEquipamiento',
    height: 400,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Equipping'),
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
                name: 'equipamiento_id',
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'equipamiento_nombre',
                fieldLabel: t('Name') + '*',
                allowBlank: false
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% -47',
                name: 'equipamiento_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// grid con la lista de técnicas
Ext.define('RESCATE.grid.EquipamientoLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.grid.EquipamientoLista',
    title: t('List'),
    soloIconos: true,
    soloIconosEdicion: true,
    botonAgregar: CONFIG.perms.administrar_equipamientos,
    botonEditar: CONFIG.perms.administrar_equipamientos,
    botonBorrar: CONFIG.perms.administrar_equipamientos,
    formulario: 'editarEquipamiento',
    mensajeAgregar: t('Add Equipping'),
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'equipamiento_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'equipamiento_nombre',
        sortable: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'equipamiento_detalles',
        sortable: true
    }],
    initComponent: function ()
    {
		this.cargarStore();
        this.callParent(arguments);
    },
	cargarStore: function()
	{
		this.store = Ext.create('widget.store.Equipamiento', {});
	}
});

// grid con la lista de técnicas en el menúprincipal
Ext.define('RESCATE.panel.EquipamientoLista', {
    extend: 'RESCATE.grid.EquipamientoLista',
    soloIconos: false,
    soloIconosEdicion: false,
	cargarStore: function()
	{
		this.store = Ext.create('widget.store.Equipamiento', {});
        this.store.getProxy().extraParams.norel = 1;
	}
});

// grid con la lista editable de técnicas
Ext.define('RESCATE.grid.Equipamiento', {
    alias: 'widget.grid.Equipamiento',
    extend: 'RESCATE.grid.Drop',
    title: t('Equipping'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: true,
    botonBorrar: true,
    formulario: 'editarEquipamientoProfesional',
    mensajeAgregar: t('Add Tech'),
    deleteParam1: 'equipamiento_id',
    deleteParam2: 'equipamientoprofesional_profesional_id',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'equipamiento_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        width: 180,
        dataIndex: 'equipamiento_nombre',
        sortable: true
    }, {
        text: t('Details'),
        dataIndex: 'equipamientoprofesional_detalles',
        sortable: true,
        flex: 1
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.EquipamientoProfesional', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de técnicas y el grid editable de técnicas
Ext.define('RESCATE.panel.Equipamiento', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.Equipamiento',
    title: t('Equipping'),
    idRelacion: 'campoIdProfesional',
    gridEditXtype: 'grid.Equipamiento',
    gridEditItemId: 'gridEquipamiento',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'equipamientoGridDDGroup',
    gridListXtype: 'grid.EquipamientoLista',
    gridListItemId: 'gridEquipamientoLista'
});