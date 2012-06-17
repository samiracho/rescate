// grid con la lista de intervencions
Ext.define('RESCATE.panel.Intervencion', {
    alias: 'widget.panel.Intervencion',
    extend: 'RESCATE.grid.grid',
    formulario: 'editarIntervencion',
    mensajeAgregar: t('Add Intervention'),
    botonMarcar: true,
    stateful: true,
    stateId: 'statePanelIntervencion',
    title: t('Interventions List'),
    idMarcar: 'intervencion_supervisado',
    columns: [
    {
        text: t('Id'),
        width: 50,
        dataIndex: 'intervencion_id',
        sortable: true,
        hidden: true
    }, {
        text: t('Name'),
        flex: 1,
        dataIndex: 'intervencion_nombre',
        sortable: true
    }, {
        text: t('Work Of Art Name'),
        flex: 1,
        dataIndex: 'obra_nombre',
        sortable: true
    }, {
        text: t('Start Date'),
        flex: 1,
        dataIndex: 'intervencion_fechainicio',
        sortable: true,
        hidden: true
    }, {
        text: t('End Date'),
        flex: 1,
        dataIndex: 'intervencion_fechafin',
        sortable: true,
        hidden: true
    }, {
        text: t('Procedures and Materials'),
        flex: 1,
        dataIndex: 'intervencion_detalles',
        sortable: true,
        hidden: true
    }, {
        text: t('Login'),
        flex: 1,
        dataIndex: 'usuario_login'
    }, {
        text: t('Creator Name'),
        flex: 1,
        dataIndex: 'usuario_nombre',
        sortable: true,
        hidden: true
    }, {
        text: t('Creator Surname 1'),
        flex: 1,
        dataIndex: 'usuario_apellido1',
        sortable: true,
        hidden: true
    }, {
        text: t('Creator Surname 2'),
        flex: 1,
        dataIndex: 'usuario_apellido2',
        sortable: true,
        hidden: true
    }, {
        text: t('Checked (0/1)'),
        width: 110,
        dataIndex: 'intervencion_supervisado',
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
        dataIndex: 'intervencion_bloqueado',
        sortable: true,
        renderer: function (val)
        {
            if (val == 1) return '<div class="iconoBloqueado"></div>';
            else
            return '<div class="iconoNoBloqueado"></div>';
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
        this.store = Ext.create('widget.store.Intervencion', {
        });
        this.callParent(arguments);
    },
    // al hacer doble click sobre una fila o hacer click sobre el botón editar. Override para controlar permisos
    onEditClick: function (button)
    {
        var record = this.getView().getSelectionModel().getSelection()[0];
        if (record)
        {
            if (CONFIG.perms.administrar_registros || (record.data['intervencion_bloqueado'] != "1" && CONFIG.perms.agregareditar_registros_propios))
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
            if (CONFIG.perms.administrar_registros || (selections[0].data['intervencion_bloqueado'] != "1" && CONFIG.perms.agregareditar_registros_propios))
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
Ext.define('RESCATE.Menu.Intervencion', {
    alias: 'widget.menuOpcionesIntervencion',
    extend: 'RESCATE.toolbar.ToolbarOpciones',
    itemIdContenedor: 'containerDetalles',
    initComponent: function ()
    {
        var me = this;
        var menu = Ext.create('Ext.menu.Menu', {
            items: [
            {
                text: t('Materials'),
                panelId: 'widget.panel.Material',
                iconCls: 'iconoMaterial',
                handler: me.onItemClick,
                scope: me
            }, {
                text: t('Procedures'),
                panelId: 'widget.panel.Procedimiento',
                iconCls: 'iconoProcedimiento',
                handler: me.onItemClick,
                scope: me
            }, {
                text: t('Documents'),
                panelId: 'widget.panel.DocumentoIntervencion',
                iconCls: 'iconoDocumento',
                handler: me.onItemClick,
                scope: me
            }]
        });
        me.items = [
        {
            text: t('Options Menu'),
            menu: menu,
            iconCls: 'iconoOpciones'
        }];
        me.callParent(arguments);
    }
});
Ext.define('RESCATE.form.editarIntervencion', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarIntervencion',
    margin: '',
    padding: '',
    width: 780,
    height: 540,
    minHeight: 540,
    maximizable: true,
    title: t('Intervention'),
    initComponent: function ()
    {
        var me = this;
		
		var anyoStore = Ext.create('widget.store.Anyo', {});
		
        var creadorStore = Ext.create('widget.store.Creador', {});
		
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
                                name: 'intervencion_nombre',
                                allowBlank: false,
                                fieldLabel: t('Name') + '*'
                            }, {
                                xtype: 'textfield',
                                name: 'intervencion_estadoconservacion',
                                fieldLabel: t('Conservation State')
                            }, {
                                xtype: 'hiddenfield',
                                name: 'intervencion_id',
                                itemId: 'campoIdIntervencion',
                                value: ''
                            }, {
                                xtype: 'hiddenfield',
                                name: 'intervencion_obra_id',
                                allowBlank: false
                            }]
                        }, {
                            xtype: 'container',
                            layout: {
                                type: 'anchor'
                            },
                            columnWidth: 0.5,
                            items: [
                            {
								xtype: 'form.ComboBox',
								displayField: 'anyo',
								valueField: 'anyo',
								name: 'intervencion_fechainicio',
								store: anyoStore,
								fieldLabel: t('Start Date')
							},{
								xtype: 'form.ComboBox',
								displayField: 'anyo',
								valueField: 'anyo',
								name: 'intervencion_fechafin',
								store: anyoStore,
								fieldLabel: t('End Date')
							}]
                        }]
                    }, {
                        xtype: 'tabpanel',
                        flex: 1,
                        plain: true,
                        items: [
                        {
                            xtype: 'panel',
                            layout: 'anchor',
                            title: t('Proc. Description'),
                            items: {
                                xtype: CONFIG.textArea,
                                anchor: '100% 100%',
                                name: 'intervencion_descprocedimiento'
                            }
                        }, {
                            xtype: 'panel',
                            title: 'Observaciones',
                            layout: 'anchor',
                            items: {
                                xtype: CONFIG.textArea,
                                anchor: '100% 100%',
                                name: 'intervencion_detalles'
                            }
                        }, {
                            xtype: 'panel',
                            layout: 'anchor',
                            title: t('Theorical Principles'),
                            items: {
                                xtype: CONFIG.textArea,
                                anchor: '100% 100%',
                                name: 'intervencion_principiosteoricos'
                            }
                        }]
                    }]
                }, {
                    xtype: 'grid.ObraSeleccionar',
                    nameRelacionCentro: 'intervencion_obra_id'
                }, {
                    xtype: 'panel',
                    padding: '',
                    layout: {
                        align: 'stretch',
                        type: 'hbox'
                    },
                    title: t('Procedures and Materials'),
                    itemId: 'panelDetalles',
                    // como no estamos destruyendo el contenido del container, ponemos como activo un container vacío y así al seleccionar una opción refrescará el grid
                    listeners: {
                        activate: function (component)
                        {
                            component.down('#containerDetalles').layout.setActiveItem(0);
                            component.down('#menuOpcionesIntervencion').down('button').showMenu();
                        }
                    },
                    dockedItems: {
                        xtype: 'menuOpcionesIntervencion',
                        itemId: 'menuOpcionesIntervencion'
                    },
                    items: [
                    {
                        xtype: 'container',
                        itemId: 'containerDetalles',
                        layout: {
                            type: 'card'
                        },
                        border: 0,
                        flex: 1,
                        items: {
                            xtype: 'container'
                        },
                        viewConfig: {
                            loadMask: false // en formularios complejos se produce un error si esta opción está activada. En la versión 4.1 deberían haberlo resuelto
                        }
                    }]
                }, {
                    xtype: 'panel.IntervencionProfesional',
                    title: t('Professionals'),
                    itemId: 'panelProfesionales'
                }, {
                    xtype: 'panel',
                    padding: 6,
                    itemId: 'panelAdministracion',
                    title: t('Administration'),
                    items: [
                    {
                        xtype: 'checkboxfield',
                        name: 'intervencion_supervisado',
                        fieldLabel: t('Checked'),
                        boxLabel: t('Yes'),
                        uncheckedValue: 0
                    }, {
                        xtype: 'checkboxfield',
                        name: 'intervencion_bloqueado',
                        fieldLabel: t('Blocked'),
                        boxLabel: t('Yes'),
                        uncheckedValue: 0
                    }, {
                        xtype: 'form.ComboBox',
                        displayField: 'nombre',
                        valueField: 'id',
                        name: 'intervencion_usuario_id',
                        store: creadorStore,
                        fieldLabel: t('Creator'),
                        emptyText: t('Me'),
                        width: 400
                    }, {
                        xtype: 'textfield',
                        name: 'intervencion_ultimamod',
                        readOnly: true,
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
        var idIntervencion = me.down('[name=intervencion_id]').getValue();
        var tabDetalles = me.down('#panelDetalles');
        var tabProfesionales = me.down('#panelProfesionales');
        var bloq = (idIntervencion == '') ? true : false;
        this.BloqDebloqTab(tabDetalles, bloq);
        this.BloqDebloqTab(tabProfesionales, bloq);
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
                var campoIdProfesional = form.down('[name=intervencion_id]');
                // si era un registro nuevo actualizamos la id del profesional en el formulario para poder activar las tabs
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
// grid con la lista de intervenciones
Ext.define('RESCATE.grid.IntervencionProfesionalLista', {
    extend: 'RESCATE.grid.grid',
    alias: 'widget.grid.IntervencionProfesionalLista',
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
        this.store = Ext.create('widget.store.IntervencionProfesional', {
        });
        this.callParent(arguments);
    }
});
// grid con la lista editable de intervenciones
Ext.define('RESCATE.grid.IntervencionProfesional', {
    alias: 'widget.grid.IntervencionProfesional',
    extend: 'RESCATE.grid.Drop',
    title: t('Associated Professionals'),
    soloIconos: true,
    botonAgregar: false,
    formulario: 'editarIntervencionProfesional',
    mensajeAgregar: t('Add Club'),
    deleteParam1: 'intervencionprofesional_intervencion_id',
    deleteParam2: 'intervencionprofesional_profesional_id',
    nombreRelacion: 'intervencionprofesional_intervencion_id',
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
    }, {
        text: 'OK',
        width: 30,
        dataIndex: 'intervencionprofesional_cargo',
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
        text: t('Cargo'),
        width: 160,
        dataIndex: 'intervencionprofesional_cargo',
        sortable: true,
        hidden: true
    }],
    initComponent: function ()
    {
        this.store = Ext.create('widget.store.IntervencionProfesional', {
        });
        this.callParent(arguments);
    }
});
// panel que une el grid de listado de colaboradores y el grid editable de colaboradores
Ext.define('RESCATE.panel.IntervencionProfesional', {
    extend: 'RESCATE.container.dragDrop',
    alias: 'widget.panel.IntervencionProfesional',
    title: '',
    idRelacion: 'campoIdIntervencion',
    gridEditXtype: 'grid.IntervencionProfesional',
    gridEditItemId: 'gridIntervencionProfesional',
    // identificador para el drag & drop. Como aquí solo queremos arrastrar desde una lista a la otra, solo necesitamos un identificador
    DDGroup: 'IntervencionProfesionalGridDDGroup',
    gridListXtype: 'grid.IntervencionProfesionalLista',
    gridListItemId: 'gridIntervencionProfesionalLista'
});
//formulario editar intervencionprofesional
Ext.define('RESCATE.form.editarIntervencionProfesional', {
    extend: 'RESCATE.form.editar',
    alias: 'widget.form.editarIntervencionProfesional',
    height: 320,
    width: 550,
    layout: {
        type: 'fit'
    },
    title: t('Professional-Intervention'),
    maximizable: true,
    initComponent: function ()
    {
        var me = this;
        var tiposStore = Ext.create('widget.store.CargosIntervencionProfesional', {
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
                name: 'intervencionprofesional_intervencion_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: 'form.ComboBox',
                displayField: 'nombre',
                valueField: 'nombre',
                name: 'intervencionprofesional_cargo',
                store: tiposStore,
                fieldLabel: t('Cargo') + '*',
                emptyText: 'Cargo',
                allowBlank: false
            }, {
                xtype: 'textfield',
                name: 'intervencionprofesional_profesional_id',
                allowBlank: false,
                hidden: true
            }, {
                xtype: CONFIG.textArea,
                fieldLabel: t('Details'),
                anchor: '100% 100%',
                name: 'intervencionprofesional_detalles',
                margin: '0 0 50 0'
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