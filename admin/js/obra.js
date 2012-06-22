// grid con la lista de obras
Ext.define('RESCATE.panel.Obra', {
    alias: 'widget.panel.Obra',
    extend: 'RESCATE.grid.grid',
    formulario: 'editarObra',
    title: t('Works Of Art List'),
    mensajeAgregar: t('Add Work Of Art'),
    botonMarcar: true,
    stateful: true,
    stateId: 'statePanelObra',
    idMarcar: 'obra_supervisado',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'obra_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Width (cm)'),
        flex: 1,
        dataIndex: 'obra_dimension_anchura',
        sortable: true,
        hidden: true
    }, {
        text: t('Height (cm)'),
        flex: 1,
        dataIndex: 'obra_dimension_altura',
        sortable: true,
        hidden: true
    }, {
        text: t('Deep (cm)'),
        flex: 1,
        dataIndex: 'obra_dimension_profundidad',
        sortable: true,
        hidden: true
    },{
        text: t('m2'),
        flex: 1,
        dataIndex: 'obra_dimension_m2',
        sortable: true,
        hidden: true
    }, {
        text: t('Country'),
        flex: 1,
        dataIndex: 'obra_pais',
        sortable: true,
        hidden: true
    }, {
        text: t('State/Province'),
        flex: 1,
        dataIndex: 'obra_provincia',
        sortable: true,
        hidden: true
    }, {
        text: t('City'),
        flex: 1,
        dataIndex: 'obra_poblacion',
        sortable: true,
        hidden: true
    }, {
        text: t('Work Of Art Name'),
        flex: 1,
        dataIndex: 'obra_nombre',
        sortable: true
    }, {
        text: t('Author'),
        flex: 1,
        dataIndex: 'profesional_nombre',
        sortable: true
    }, {
        text: t('Surname 1'),
        flex: 1,
        dataIndex: 'profesional_apellido1',
        sortable: true
    }, {
        text: t('Surname 2'),
        flex: 1,
        dataIndex: 'profesional_apellido2',
        sortable: true,
        hidden: true
    }, {
        text: t('Current Location Name'),
        flex: 1,
        dataIndex: 'ubicacion_nombre',
        sortable: true,
        hidden: true
    }, {
        text: t('Current Location Country'),
        flex: 1,
        dataIndex: 'ubicacion_pais',
        sortable: true,
        hidden: true
    }, {
        text: t('Current Location State/Province'),
        flex: 1,
        dataIndex: 'ubicacion_provincia',
        sortable: true,
        hidden: true
    }, {
        text: t('Current Location City'),
        flex: 1,
        dataIndex: 'ubicacion_poblacion',
        sortable: true,
        hidden: true
    }, {
        text: t('Start Date'),
        flex: 1,
        dataIndex: 'obra_fecha1',
        sortable: true,
        hidden: true
    }, {
        text: t('Details'),
        flex: 1,
        dataIndex: 'obra_detalles',
        sortable: true,
        hidden: true
    }, {
        text: t('Checked (0/1)'),
        width: 110,
        dataIndex: 'obra_supervisado',
        sortable: true,
        renderer: function (val)
        {
            if (val == 1) return '<div class="iconoSupervisado"></div>';
            else
            return '<div class="iconoNoSupervisado"></div>'
        }
    }, {
        text: t('Blocked (0/1)'),
        width: 110,
        dataIndex: 'obra_bloqueado',
        sortable: true,
        renderer: function (val)
        {
            if (val == 1) return '<div class="iconoBloqueado"></div>';
            else
            return '<div class="iconoNoBloqueado"></div>'
        }
    }],
    // propiedades adicionales de nuestro grid personalizado
    initComponent: function ()
    {
        if (!CONFIG.perms.administrar_registros && !CONFIG.perms.agregareditar_registros_propios)
        {
            this.botonAgregar = false;
            this.botonEditar = false;
        }
        this.store = Ext.create('widget.store.Obra', {
        });
        this.callParent(arguments);
    },
    // al hacer doble click sobre una fila o hacer click sobre el botón editar. Override para controlar permisos
    onEditClick: function (button)
    {
        var record = this.getView().getSelectionModel().getSelection()[0];
        if (record)
        {
            if (CONFIG.perms.administrar_registros || (record.data['obra_bloqueado'] != "1" && CONFIG.perms.agregareditar_registros_propios))
            {
                // si está en modo lista no hacemos nada
                if (!this.botonEditar) return false;
                var record = this.getView().getSelectionModel().getSelection()[0];
                if (record) this.abrirFormularioEdicion(record);
            }else{
				Ext.MessageBox.alert(
				{
					title: t('Warning'),
					msg: t('You dont have the required permissions'),
					buttons: Ext.MessageBox.OK,
					icon: Ext.MessageBox.ERROR
				});		
			}
        }
        return false;
    },
    // al seleccionar una fila activamos los botones editar y eliminar siempre que el registro no esté bloqueado
    onSelectChange: function (selModel, selections)
    {
        if (selections.length != 0)
        {
            // si es administrador o el registro no está bloqueado
            if (CONFIG.perms.administrar_registros || (selections[0].data['obra_bloqueado'] != "1" && CONFIG.perms.agregareditar_registros_propios))
            {
                if (this.botonBorrar) this.down('#botonBorrar').setDisabled(selections.length === 0);
                if (this.botonEditar) this.down('#botonEditar').setDisabled(selections.length === 0);
            }
            else
            {
                if (this.botonBorrar) this.down('#botonBorrar').setDisabled(true);
                if (this.botonEditar) this.down('#botonEditar').setDisabled(true);
            }
        }
    }
});
// grid con la lista editable de obras con seleccion
Ext.define('RESCATE.grid.ObraSeleccionar', {
    alias: 'widget.grid.ObraSeleccionar',
    extend: 'RESCATE.grid.grid',
    itemId: 'gridObraSeleccionar',
    title: t('Work Of Art') + '*',
    soloIconos: true,
    botonAgregar: false,
    botonEditar: false,
    botonBorrar: false,
    // itemId del formulario desde el que se selecciona el centro
    nameRelacionCentro: '',
    formulario: 'editarObra',
    mensajeAgregar: '',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'obra_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Width (cm)'),
        flex: 1,
        dataIndex: 'obra_dimension_anchura',
        sortable: true,
        hidden: true
    }, {
        text: t('Height (cm)'),
        flex: 1,
        dataIndex: 'obra_dimension_altura',
        sortable: true,
        hidden: true
    }, {
        text: t('Deep (cm)'),
        flex: 1,
        dataIndex: 'obra_dimension_profundidad',
        sortable: true,
        hidden: true
    }, {
        text: t('m2'),
        flex: 1,
        dataIndex: 'obra_dimension_m2',
        sortable: true,
        hidden: true
    }, {
        text: t('Country'),
        flex: 1,
        dataIndex: 'obra_pais',
        sortable: true,
        hidden: true
    }, {
        text: t('State/Province'),
        flex: 1,
        dataIndex: 'obra_provincia',
        sortable: true,
        hidden: true
    }, {
        text: t('City'),
        flex: 1,
        dataIndex: 'obra_poblacion',
        sortable: true,
        hidden: true
    }, {
        text: t('Work Of Art Name'),
        flex: 1,
        dataIndex: 'obra_nombre',
        sortable: true
    }, {
        text: t('Author'),
        flex: 1,
        dataIndex: 'profesional_nombre',
        sortable: true
    }, {
        text: t('Surname 1'),
        flex: 1,
        dataIndex: 'profesional_apellido1',
        sortable: true,
        hidden: true
    }, {
        text: t('Surname 2'),
        flex: 1,
        dataIndex: 'profesional_apellido2',
        sortable: true,
        hidden: true
    }, {
        text: t('Current Location Name'),
        text: t('Date'),
        flex: 1,
        dataIndex: 'ubicacion_nombre',
        sortable: true,
        hidden: true
    }, {
        text: t('Current Location Country'),
        flex: 1,
        dataIndex: 'ubicacion_pais',
        sortable: true,
        hidden: true
    }, {
        text: t('Current Location State/Province'),
        flex: 1,
        dataIndex: 'ubicacion_provincia',
        sortable: true,
        hidden: true
    }, {
        text: t('Current City'),
        flex: 1,
        dataIndex: 'ubicacion_poblacion',
        sortable: true,
        hidden: true
    }, {
        text: t('Start Date'),
        flex: 1,
        dataIndex: 'obra_fecha1',
        sortable: true,
        hidden: true
    }, {
        text: t('Details'),
        flex: 1,
        dataIndex: 'obra_detalles',
        sortable: true,
        hidden: true
    }],
    viewConfig: {
        listeners: {
            itemclick: function (view, rec, node, index, e)
            {
                var formulario = view.up('form');
                var campoIdObra = formulario.down('hiddenfield[name=' + view.ownerCt.nameRelacionCentro + ']');
                campoIdObra.setValue(rec.data['obra_id']);
            }
        }
    },
    initComponent: function ()
    {
        var me = this;
        var formulario = me.up('form');
        me.store = Ext.create('widget.store.Obra', {
        });
        me.selModel = Ext.create('Ext.selection.CheckboxModel', {
            mode: 'SINGLE',
            searchable: false
        });
        // cuando cargue si obra_id no es nulo, lo seleccionamos en la lista
        me.getStore().on('load', function (store)
        {
            var formulario = me.up('form');
            var valorIdObra = formulario.down('hiddenfield[name=' + me.nameRelacionCentro + ']').getValue();
            var totalRecords = store.getTotalCount();
            if (valorIdObra != "")
            {
                var recordIndex = store.findExact('obra_id', valorIdObra);
                if (recordIndex != -1) me.getSelectionModel().select(recordIndex);
            }
        });
        // cuando cargue si obra_id no es nulo, lo seleccionamos en la lista y la ponemos la primera
        me.getStore().on('beforeload', function (store)
        {
            var formulario = me.up('form');
            var valorIdObra = formulario.down('hiddenfield[name=' + me.nameRelacionCentro + ']').getValue();
            me.store.getProxy().extraParams.idObra = valorIdObra;
        });
        me.callParent(arguments);
    }
});
Ext.define('RESCATE.form.editarObra', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarObra',
    margin: '',
    padding: '',
    width: 780,
    height: 540,
    maximizable: true,
    title: t('Work Of Art'),
    initComponent: function ()
    {
        var me = this;
		var anyoStore = Ext.create('widget.store.Anyo', {inicio:0});
        var sexoStore = Ext.create('widget.store.Sexo', {});
        var creadorStore = Ext.create('widget.store.Creador', {});
        var paisStore = Ext.create('widget.store.Pais', {});
        var provinciaStore = Ext.create('widget.store.Provincia', {});
        paisStore.load();
        provinciaStore.load();
        me.items = [
        {
            xtype: 'form',
            bodyPadding: 10,
            layout: {
                type: 'fit'
            },
            fieldDefaults: {
                msgTarget: 'side',
                labelAlign: 'top',
                anchor: '96%'
            },
            title: '',
            items: [
            {
                xtype: 'tabpanel',
                deferredRender: true,
                activeTab: 0,
                plain: true,
                items: [
                {
                    xtype: 'panel',
                    layout: {
                        align: 'stretch',
                        padding: 6,
                        type: 'vbox'
                    },
                    title: t('General') + '*',
                    items: [
                    {
                        xtype: 'container',
                        layout: {
                            type: 'column'
                        },
                        items: [
                        {
                            xtype: 'container',
                            layout: {
                                type: 'anchor'
                            },
                            columnWidth: 0.5,
                            items: [
                            {
                                xtype: 'textfield',
                                name: 'obra_nombre',
                                fieldLabel: t('Title') + '*',
                                allowBlank: false
                            },{
								xtype: 'fieldcontainer',
								layout:'hbox',
								msgTarget: 'under',
								defaults: {
									labelAlign: 'top'
								},
								items: [
									{fieldLabel:t('Date (Year)'), xtype: 'form.ComboBox',displayField: 'anyo',valueField: 'anyo',name: 'obra_fecha1',flex:1,store: anyoStore, margins: '0 5 0 0'},
									{fieldLabel:'Siglo', xtype: 'textfield', name: 'obra_siglo', width: 100, margins: '0 5 0 0'},
									{fieldLabel:'a. C - d .C',xtype: 'form.ComboBox', displayField: 'acdc',valueField: 'acdc',name: 'obra_acdc', width: 100,store:new Ext.data.ArrayStore({fields: ['acdc'],data : [['a. C.'],['d. C.']]})}
								]
							}, {
                                xtype: 'hiddenfield',
                                name: 'obra_id',
                                itemId: 'campoIdObra',
                                value: ''
                            }, {
                                xtype: 'numberfield',
                                name: 'obra_dimension_m2',
                                fieldLabel: t('Superficie (m2)'),
                                minValue: 0,
                                maxValue: 10000,
                                maxlength: 5
                            }]
                        }, {
                            xtype: 'container',
                            layout: {
                                type: 'anchor'
                            },
                            columnWidth: 0.5,
                            items: [
                            {
                                xtype: 'numberfield',
                                name: 'obra_dimension_altura',
                                fieldLabel: t('Height (cm)'),
                                minValue: 0,
                                maxValue: 10000,
                                maxlength: 5
                            }, {
                                xtype: 'numberfield',
                                name: 'obra_dimension_anchura',
                                fieldLabel: t('Width (cm)'),
                                minValue: 0,
                                maxValue: 10000,
                                maxlength: 5
                            }, {
                                xtype: 'numberfield',
                                name: 'obra_dimension_profundidad',
                                fieldLabel: t('Deep (cm)'),
                                minValue: 0,
                                maxValue: 10000,
                                maxlength: 5
                            }]
                        }]
                    }, {
                        xtype: CONFIG.textArea,
                        flex: 1,
                        name: 'obra_detalles',
                        fieldLabel: t('Description'),
                        anchor: '100% -5'
                    }]
                }, {
                    xtype: 'panel.ProfesionalObra',
                    title: t('Artist'),
                    itemId: 'panelartista'
                }, {
                    xtype: 'panelDireccion',
                    paisName: 'obra_pais',
                    provinciaName: 'obra_provincia',
                    poblacionName: 'obra_poblacion',
                    direccionName: 'obra_direccion',
                    cordenadasName: 'obra_cordenadas',
                    title: t('Original Location'),
                    paisStore: paisStore,
                    provinciaStore: provinciaStore
                }, {
                    xtype: 'panel.Ubicacion',
                    title: t('Current Location'),
                    itemId: 'panelubicacion'
                }, {
                    xtype: 'panel.Metodo',
                    title: t('Technnic'),
                    itemId: 'panelMetodos'
                }, {
                    xtype: 'panel.DocumentoObra',
                    title: t('Documents'),
                    itemId: 'paneldocumentos'
                }, {
                    xtype: 'panel',
                    padding: 6,
                    itemId: 'panelAdministracion',
                    title: t('Administration'),
                    items: [
                    {
                        xtype: 'checkboxfield',
                        name: 'obra_supervisado',
                        fieldLabel: t('Checked'),
                        boxLabel: t('Yes'),
                        uncheckedValue: 0
                    }, {
                        xtype: 'checkboxfield',
                        name: 'obra_bloqueado',
                        fieldLabel: t('Blocked'),
                        boxLabel: t('Yes'),
                        uncheckedValue: 0
                    }, {
                        xtype: 'form.ComboBox',
                        displayField: 'nombre',
                        valueField: 'id',
                        name: 'obra_usuario_id',
                        store: creadorStore,
                        fieldLabel: t('Creator'),
                        emptyText: t('Me'),
                        width: 400
                    }, {
                        xtype: 'textfield',
                        name: 'obra_ultimamod',
                        readOnly: true,
                        disabled: true,
                        fieldLabel: t('Last Modification'),
                        width: 200
                    }]
                }]
            }],
            dockedItems: me.barraBotones()
        }];
        // Datoscargados es un evento que se dispara cuando el record ya ha sido cargado en los campos del formulario
        me.addListener('datoscargados', function (component)
        {
            creadorStore.load();
			anyoStore.load();
        });
        me.callParent(arguments);
    },
    EstablecerPermisos: function ()
    {
        var me = this;
		// Si no es administrador desactivamos el panel de administracion
        var tabAdministracion = me.down('#panelAdministracion');
        if (!CONFIG.perms.administrar_registros)
        {
            this.BloqDebloqTab(tabAdministracion, true);
            tabAdministracion.tab.setTooltip(
            {
                title: t('Option Blocked'),
                text: t('You dont have the required permissions')
            });
        }
        var idObra = me.down('[name=obra_id]').getValue();
        var tabDocumentos = me.down('#paneldocumentos');
        var tabArtista = me.down('#panelartista');
        var tabMetodos = me.down('#panelMetodos');
		var tabUbicacion = me.down('#panelubicacion');
        var bloq = (idObra == '') ? true : false;
        this.BloqDebloqTab(tabDocumentos, bloq);
        this.BloqDebloqTab(tabArtista, bloq);
        this.BloqDebloqTab(tabMetodos, bloq);
		this.BloqDebloqTab(tabUbicacion, bloq);
    },
    //override del metodo que se ejecuta después de sincronizar
    SyncCallBack: function ()
    {
        var me = this;
		Ext.getBody().unmask();
        var form = me.down('form');
        var record = form.getRecord();
        var store = me.grid.getStore();
        store.proxy.ventana = null;
        Ext.MessageBox.confirm(t('Confirmation'), t('Success saving <br />Do you want to continue editing?'), function (btn)
        {
            if (btn == 'no')
            {
                me.close();
            }
            if (btn == 'yes')
            {
                var campoIdProfesional = form.down('[name=obra_id]');
                // si era un registro nuevo actualizamos la id de la obra en el formulario para poder activar las tabs
                if (campoIdProfesional.getValue() == '')
                {
                    // esto debo hacerlo para que al volver a darle a aceptar, actualice el record que habíamos insertado previamente
                    // sino considera que no hay nada que actualizar y no hace el sync
                    form.loadRecord(store.getAt(0));
                }
                me.EstablecerPermisos();
            }
        });
    }
});

// grid con la lista editable de profesionales relacionados con la obra
Ext.define('RESCATE.grid.ProfesionalObra', {
    alias: 'widget.grid.ProfesionalObra',
    extend: 'RESCATE.grid.DropPro',
    title: t('Author'),
    soloIconos: true,
    botonAgregar: false,
    formulario: 'editarProfesionalObra',
    mensajeAgregar: t('Add Author'),
	nombreRelacion: 'obra_id',
	deleteParam1: 'profesionalobra_obra_id',
	deleteParam2: 'profesionalobra_profesional_id',
	claveAjena1: 'profesionalobra_obra_id',
	claveAjena2: 'profesionalobra_profesional_id',
    registrosDuplicados: false,
	registroUnico: true,
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
        text: t('Details'),
        width: 100,
        dataIndex: 'profesionalobra_detalles',
        sortable: true,
        hidden: false
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.ProfesionalObra', {
        });
        this.callParent(arguments);
    }
});
//formulario editar ProfesionalObra
Ext.define('RESCATE.form.editarProfesionalObra', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarProfesionalObra',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Professional-Work Of Art'),
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
                name: 'profesionalobra_obra_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'textfield',
                name: 'profesionalobra_profesional_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'profesionalobra_detalles',
                margin: '0'
            }],
            dockedItems: me.barraBotones()
        }];
		me.callParent(arguments);
    }
});

Ext.define('RESCATE.grid.ArtistaLista', {
    extend: 'RESCATE.grid.ProfesionalLista',
    alias: 'widget.grid.ArtistaLista',
    tipoProfesional: 'Colaborador'
});

// panel que une el grid de listado de profesionales y el grid editable de profesionales
Ext.define('RESCATE.panel.ProfesionalObra', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.ProfesionalObra',
    title: '',
    idRelacion: 'campoIdObra',
    gridEditXtype: 'grid.ProfesionalObra',
    gridEditItemId: 'gridProfesionalObra',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'ProfesionalObraGridDDGroup',
    gridListXtype: 'grid.ArtistaLista',
    gridListItemId: 'gridArtistaLista'
});