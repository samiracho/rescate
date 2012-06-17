Ext.define('RESCATE.form.editarColaborador', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarColaborador',
    height: 320,
    minHeight: 320,
    width: 500,
    minWidth: 500,
    layout: {
        type: 'fit'
    },
    title: t('Collaborator'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        var tiposStore = Ext.create('widget.store.TiposColaboradores', {
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
                name: 'colaborador_profesional_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'colaborador_colaborador_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'form.ComboBox',
                displayField: 'nombre',
                valueField: 'nombre',
                name: 'colaborador_tipo',
                store: tiposStore,
                fieldLabel: t('Type') + '*',
                emptyText: 'Tipo',
                allowBlank: false
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% -47',
                name: 'colaborador_detalles'
            }],
            dockedItems: me.barraBotones()
        }];
        me.addListener('datoscargados', function (component)
        {
            tiposStore.load();
        });
        me.callParent(arguments);
    }
});
// grid con la lista de colaboradores
Ext.define('RESCATE.grid.ColaboradorLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.grid.ColaboradorLista',
    title: t('List'),
    soloIconos: true,
    botonAgregar: false,
    botonEditar: false,
    botonBorrar: false,
    columns: [
    {
        text: t('Name'),
        flex: 1,
        dataIndex: 'profesional_nombre',
        sortable: true
    }, {
        text: t('Surname 1'),
        width: 160,
        dataIndex: 'profesional_apellido1',
        sortable: true
    }, {
        text: t('Surname 2'),
        width: 160,
        dataIndex: 'profesional_apellido2',
        sortable: true,
        hidden: true
    }, {
        text: t('Type'),
        width: 160,
        dataIndex: 'profesional_tipo',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Colaborador', {
        });
        this.callParent(arguments);
    }
});
// grid con la lista editable de colaboradores
Ext.define('RESCATE.grid.Colaborador', {
    alias: 'widget.grid.Colaborador',
    extend: 'RESCATE.grid.Drop',
    title: t('Asocciated Collaborators'),
    soloIconos: true,
    botonAgregar: false,
    formulario: 'editarColaborador',
    mensajeAgregar: t('Add Club'),
    deleteParam1: 'colaborador_colaborador_id',
    deleteParam2: 'colaborador_profesional_id',
    nombreRelacion: 'colaborador_profesional_id',
    columns: [
    {
        text: t('Name'),
        flex: 1,
        dataIndex: 'profesional_nombre',
        sortable: true
    }, {
        text: t('Surname 1'),
        width: 160,
        dataIndex: 'profesional_apellido1',
        sortable: true
    }, {
        text: t('Surname 2'),
        width: 160,
        dataIndex: 'profesional_apellido2',
        sortable: true,
        hidden: true
    }, {
        text: 'OK',
        width: 30,
        dataIndex: 'colaborador_tipo',
        sortable: true,
        renderer: function (value, metadata, record, rowIndex, colIndex, store)
        {
            if (value) return '<div class="iconoSupervisado"></div>';
            else
            {
                metadata.tdAttr = 'data-qtip="' + t('You must edit it to complete the details') + '"';
                return '<div class="iconoNoSupervisado"></div>';
            }
        }
    }, {
        text: t('Type'),
        width: 160,
        dataIndex: 'colaborador_tipo',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.Colaborador', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de colaboradores y el grid editable de colaboradores
Ext.define('RESCATE.panel.Colaborador', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.Colaborador',
    title: t('Collaborators'),
    idRelacion: 'campoIdProfesional',
    gridEditXtype: 'grid.Colaborador',
    gridEditItemId: 'gridColaborador',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'colaboradorGridDDGroup',
    gridListXtype: 'grid.ColaboradorLista',
    gridListItemId: 'gridColaboradorLista'
});