// formulario para editar especialidad-profesional
Ext.define('RESCATE.form.editarEspecialidadProfesional', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarEspecialidadProfesional',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Professional-Specialty'),
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
                name: 'especialidad_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'especialidadprofesional_profesional_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'especialidadprofesional_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// formulario para editar especialidades
Ext.define('RESCATE.form.editarEspecialidad', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarEspecialidad',
    height: 400,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Specialities'),
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
                name: 'especialidad_id',
                hidden: true
            }, {
                xtype: 'textfield',
                fieldLabel: t('Name') + '*',
                name: 'especialidad_nombre',
                allowBlank: false
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% -47',
                name: 'especialidad_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.callParent(arguments);
    }
});
// grid con la lista de especialidades
Ext.define('RESCATE.grid.EspecialidadLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.grid.EspecialidadLista',
    title: t('List'),
    soloIconos: true,
    soloIconosEdicion: true,
    botonAgregar: CONFIG.perms.administrar_especialidades,
    botonEditar: CONFIG.perms.administrar_especialidades,
    botonBorrar: CONFIG.perms.administrar_especialidades,
    formulario: 'editarEspecialidad',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'especialidad_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'especialidad_nombre',
        sortable: true
    }, {
        text: t('Details'),
        width: 160,
        dataIndex: 'especialidad_detalles',
        sortable: true
    }],
    initComponent: function ()
    {
        this.cargarStore();
        this.callParent(arguments);
    },
	cargarStore: function()
	{
		this.store = Ext.create('widget.store.Especialidad', {});
	}
});
// grid con la lista de especialidades en el menú principal
Ext.define('RESCATE.panel.EspecialidadLista', {
    extend: 'RESCATE.grid.EspecialidadLista',
    soloIconos: false,
    soloIconosEdicion: false,
	cargarStore: function()
	{
		this.store = Ext.create('widget.store.Especialidad', {});
        this.store.getProxy().extraParams.norel = 1;
	}
});
// grid con la lista editable de especialidades
Ext.define('RESCATE.grid.Especialidad', {
    alias: 'widget.grid.Especialidad',
    extend: 'RESCATE.grid.Drop',
    title: t('Specialities'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: true,
    botonBorrar: true,
    formulario: 'editarEspecialidadProfesional',
    mensajeAgregar: t('Add Speciality'),
    deleteParam1: 'especialidad_id',
    deleteParam2: 'especialidadprofesional_profesional_id',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'especialidad_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        width: 180,
        dataIndex: 'especialidad_nombre',
        sortable: true
    }, {
        text: t('Details'),
        flex: 1,
        dataIndex: 'especialidadprofesional_detalles',
        sortable: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.EspecialidadProfesional', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de especialidades y el grid editable de especialidades
Ext.define('RESCATE.container.Especialidad', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.container.Especialidad',
    title: t('Specialities'),
    idRelacion: 'campoIdProfesional',
    gridEditXtype: 'grid.Especialidad',
    gridEditItemId: 'gridEspecialidad',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'especialidadGridDDGroup',
    gridListXtype: 'grid.EspecialidadLista',
    gridListItemId: 'gridEspecialidadLista'
});