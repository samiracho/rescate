// Plugin para agregar una barra de herramientas con los botones de agregar, editar, borrar, b�squeda y refrescar
Ext.define('Ext.ux.BarraHerramientasPanel', {
    // para mostrar solo los iconos y no los textos en buscar y refrescar
    soloIconos: false,
    // para mostrar solo los iconos y no los textos en agregar editar y refrescar
    soloIconosEdicion: false,
    // si es false ocultamos los iconos de agregar,editar,eliminar
    botonAgregar: false,
    botonEditar: false,
    botonBorrar: false,
    botonMarcar: false,
	botonBuscar:false,
    idMarcar: '',
    constructor: function (config)
    {
        Ext.apply(this, config);
        this.callParent(arguments);
    },
    init: function (panel)
    {
        var me = this;
        if (!panel.onAddClick) panel.onAddClick = function ()
        {
            if (!this.botonAgregar) return false;
            // creamos un record vacío
            var record = Ext.create(this.getStore().model, {
            });
            if (this.idRelacion != '' && this.nombreRelacion != '')
            {
                var id = this.up('form').down('#' + this.idRelacion).getValue();
                var name = this.nombreRelacion;
                record.set(name, id);
            }
            this.abrirFormularioEdicion(record);
        };
        if (!panel.abrirFormularioEdicion) panel.abrirFormularioEdicion = function (record)
        {
            Ext.getBody().mask(t('Loading...'));
            // introducimos un retardo de 1 ms para que en estupido internet explorer se muestre la barra de carga		
            Ext.Function.defer(function ()
            {
                // no destruimos las ventanas, solo las ocultamos para evitar fugas de memoria en estupido internet explorer
                if (!this.formEdicion)
                {
                    var view = Ext.widget('form.' + this.formulario);
                    view.grid = this;
                    this.formEdicion = view;
                }
                this.formEdicion.CargarRecord(record);
            }, 1, this);
        };
        // al hacer doble click sobre una fila
        if (!panel.onEditClick) panel.onEditClick = function (button)
        {
            // si está en modo lista no hacemos nada
            if (!this.botonEditar) return false;
            var record = this.getView().getSelectionModel().getSelection()[0];
            if (record) this.abrirFormularioEdicion(record);
        };
        // al hacer click en el botón refrescar
        if (!panel.onRefreshClick) panel.onRefreshClick = function (button)
        {	
			// le quitamos los filtros de búsqueda
            this.store.getProxy().extraParams.filtros = '';
            // recargamos el store
            var lstOptions = this.store.lastOptions ? this.store.lastOptions.push(
            {
                'refrescar': true
            }) : {
                params: {
                    refrescar: true
                }
            };
            this.store.load(lstOptions);
            // algunas veces al cambiar el tamaño del grid aparace una fila vacía, esto obliga a redibujar el control para hacerla desaparecer. Se supone que se solucionará en la 4.1
            //if (this instanceof Ext.grid.Panel) this.forceComponentLayout();
        };
        // al hacer doble click sobre una fila
        if (!panel.onSearchClick) panel.onSearchClick = function (button, toggle)
        {
            if (!toggle)
            {
                this.onRefreshClick();
                return true;
            }
            if (!this.dialogoBusqueda)
            {
                this.dialogoBusqueda = Ext.widget('window.busqueda');
            }
            var filtros = new Array();
            for (i = 0; i < this.columns.length; i++)
            {
                // el 160 es el texto que tiene una columna de tipo checkbox, así la ignoramos
                if (this.columns[i].searchable !== false && this.columns[i].text != '&#160;')
                {
                    var filtro =
                    {
                        'nombre': this.columns[i].text,
                        'valor': this.columns[i].dataIndex
                    };
                    filtros.push(filtro);
                }
            }
            var store = Ext.create('Ext.data.Store', {
                model: 'RESCATE.model.Filtro',
                data: filtros
            });
            // guardamos una referencia al store del grid para poder hacer filtrarlo
            this.dialogoBusqueda.storeBusqueda = this.store;
            this.dialogoBusqueda.definirCamposBusqueda(store);
            button.toggle(false, true);
            this.dialogoBusqueda.botonBusqueda = button;
            this.dialogoBusqueda.show();
        };
        // funciones de los botones
        if (!panel.onDeleteClick) panel.onDeleteClick = function (button)
        {
            if (!this.botonBorrar) return false;
            var selection = this.getView().getSelectionModel().getSelection()[0];
            var store = button.up('panel').getStore();
            var errores = true;
            if (selection)
            {
                Ext.Msg.show(
                {
                    title: 'Confirm',
                    msg: t('Are you sure you want to delete?'),
                    buttons: Ext.Msg.YESNO,
                    closable: false,
                    fn: function (btn)
                    {
                        if (btn == 'yes') store.eliminar(selection, {
                            'id': selection.getId()
                        });
                    }
                });
            }
        };
        if (!panel.onMarkToggle) panel.onMarkToggle = function (item, pressed)
        {
            if (!this.botonMarcar) return false;
            this.getView().getRowClass = function (record, index)
            {
                if (pressed)
                {
                    var cls = "";
                    if (record.get(panel.idMarcar) == 0)
                    {
                        return 'red-row';
                    }
                    else
                    return '';
                }
                else
                return '';
            }
            this.getView().refresh();
        };
        var bAgregar = me.botonAgregar ? {
            iconCls: 'iconoAgregar',
            // icono agregar          
            text: me.soloIconosEdicion ? '' : t('Add'),
            scope: panel,
            handler: panel.onAddClick,
            tooltip: {
                title: t('Add'),
                text: t('Add record'),
                xtype: "quicktip"
            }
        } : '';
        var bEditar = me.botonEditar ? {
            iconCls: 'iconoEditar',
            // icono editar usuario
            text: me.soloIconosEdicion ? '' : t('Edit'),
            disabled: true,
            scope: panel,
            handler: panel.onEditClick,
            itemId: 'botonEditar',
            tooltip: {
                title: t('Edit'),
                text: t('Edit selected record'),
                xtype: "quicktip"
            }
        } : '';
        var bBorrar = me.botonBorrar ? {
            iconCls: 'iconoBorrar',
            // icono borrar usuario
            text: me.soloIconosEdicion ? '' : t('Delete'),
            disabled: true,
            scope: panel,
            handler: panel.onDeleteClick,
            itemId: 'botonBorrar',
            tooltip: {
                title: t('Delete'),
                text: t('Delete selected record'),
                xtype: "quicktip"
            }
        } : '';
        // boton para marcar los registros no supervisados
        var bMarcar = me.botonMarcar ? {
            text: t('Highlight'),
            enableToggle: true,
            toggleHandler: panel.onMarkToggle,
            scope: panel,
            iconCls: 'iconoMarcar',
            tooltip: {
                title: t('Highlight'),
                text: t('Highlights non supervised records'),
                xtype: "quicktip"
            }
        } : '';
		// boton para buscar
        var bBuscar = me.botonBuscar ? {
            text: me.soloIconos ? '' : t('Search'),
            enableToggle: true,
            toggleHandler: panel.onSearchClick,
            scope: panel,
            iconCls: 'iconoBuscar',
            tooltip: {
                title: t('Search'),
                text: t('Search by'),
                xtype: "quicktip"
            }
        } : '';
		
        var barraHerramientas = Ext.create('Ext.toolbar.Toolbar', {
            items: [bAgregar, bEditar, bBorrar, '->', bMarcar, bBuscar, 
			{
                iconCls: 'x-tbar-loading',
                // icono refrescar
                text: me.soloIconos ? '' : t('Refresh'),
                disabled: false,
                scope: panel,
                handler: panel.onRefreshClick,
                tooltip: {
                    title: t('Refresh'),
                    text: t('Refreshes the list') + "  ",
                    xtype: "quicktip"
                }
            }]
        });
        panel.addDocked(barraHerramientas, 0);
    }
});
Ext.define('RESCATE.window.busqueda', {
    extend: 'Ext.window.Window',
    closeAction: 'hide',
    alias: 'widget.window.busqueda',
    width: 330,
    resizable: false,
    layout: {
        type: 'fit'
    },
    title: t('Search'),
    storeBusqueda: '',
    initComponent: function ()
    {
        var me = this;
        me.items = [
        {
            xtype: 'form',
            bodyPadding: 10,
			border:0,
            items: [
            {
                xtype: 'combobox',
                itemId: 'filtroBusqueda',
                queryMode: 'local',
                editable: false,
                allowBlank: false,
                fieldLabel: 'Filtrar por',
                emptyText: t('Select Filter'),
                anchor: '100%',
                store: Ext.create('widget.store.Operador', {
                }),
                displayField: 'nombre',
                valueField: 'valor'
            }, {
                xtype: 'combobox',
                itemId: 'filtroComparador',
                queryMode: 'local',
                editable: false,
                allowBlank: false,
                fieldLabel: t('Comparator'),
                emptyText: t('Select Comparator'),
                anchor: '100%',
                store: Ext.create('widget.store.Comparador', {
                }),
                displayField: 'nombre',
                valueField: 'id',
				value:'3'
            }, {
                xtype: 'textfield',
                itemId: 'textoBusqueda',
                fieldLabel: 'Texto',
                allowBlank: false,
                anchor: '100%',
                listeners: {
                    specialkey: function (f, e)
                    {
                        if (e.getKey() == e.ENTER)
                        {
                            me.onSearchClick();
                        }
                    }
                }
            }, {
                xtype: 'fieldset',
                title: t('More Options'),
                collapsible: true,
                layout: 'anchor',
                collapsed: true,
                items: [
                {
                    xtype: 'combobox',
                    itemId: 'filtroOperador2',
                    queryMode: 'local',
                    editable: false,
                    allowBlank: true,
                    fieldLabel: 'Operador',
                    anchor: '100%',
                    value: '1',
                    store: Ext.create('widget.store.Operador', {
                    }),
                    displayField: 'nombre',
                    valueField: 'id'
                }, {
					xtype: 'combobox',
					itemId: 'filtroComparador2',
					queryMode: 'local',
					editable: false,
					allowBlank: true,
					fieldLabel: t('Comparator'),
					emptyText: t('Select Comparator'),
					anchor: '100%',
					store: Ext.create('widget.store.Comparador', {
					}),
					displayField: 'nombre',
					valueField: 'id'
				}, {
                    xtype: 'combobox',
                    itemId: 'filtroBusqueda2',
                    queryMode: 'local',
                    editable: false,
                    allowBlank: true,
                    fieldLabel: 'Filtrar por',
                    emptyText: t('Select Filter'),
                    anchor: '100%',
                    store: Ext.create('widget.store.Operador', {
                    }),
                    displayField: 'nombre',
                    valueField: 'valor'
                }, {
                    xtype: 'textfield',
                    itemId: 'textoBusqueda2',
                    fieldLabel: 'Texto',
                    allowBlank: true,
                    anchor: '100%',
                    listeners: {
                        specialkey: function (f, e)
                        {
                            if (e.getKey() == e.ENTER)
                            {
                                me.onSearchClick();
                            }
                        }
                    }
                }, {
                    xtype: 'combobox',
                    itemId: 'filtroOperador3',
                    queryMode: 'local',
                    editable: false,
                    allowBlank: true,
                    fieldLabel: 'Operador',
                    anchor: '100%',
                    value: '1',
                    store: Ext.create('widget.store.Operador', {
                    }),
                    displayField: 'nombre',
                    valueField: 'id'
                }, {
					xtype: 'combobox',
					itemId: 'filtroComparador3',
					queryMode: 'local',
					editable: false,
					allowBlank: true,
					fieldLabel: t('Comparator'),
					emptyText: t('Select Comparator'),
					anchor: '100%',
					store: Ext.create('widget.store.Comparador', {
					}),
					displayField: 'nombre',
					valueField: 'id'
				}, {
                    xtype: 'combobox',
                    itemId: 'filtroBusqueda3',
                    queryMode: 'local',
                    editable: false,
                    allowBlank: true,
                    fieldLabel: 'Filtrar por',
                    emptyText: t('Select Filter'),
                    anchor: '100%',
                    store: Ext.create('widget.store.Operador', {
                    }),
                    displayField: 'nombre',
                    valueField: 'valor'
                }, {
                    xtype: 'textfield',
                    itemId: 'textoBusqueda3',
                    fieldLabel: 'Texto',
                    allowBlank: true,
                    anchor: '100%',
                    listeners: {
                        specialkey: function (f, e)
                        {
                            if (e.getKey() == e.ENTER)
                            {
                                me.onSearchClick();
                            }
                        }
                    }
                }]
            }],
            dockedItems: [
            {
                xtype: 'container',
                dock: 'bottom',
                padding: 4,
                items: [
                {
                    xtype: 'button',
                    cls: 'botonFormulario',
                    text: 'Cancelar',
                    handler: me.close,
                    scope: me
                }, {
                    xtype: 'button',
                    cls: 'botonFormulario',
                    text: 'Aceptar',
                    formBind: true,
                    handler: me.onSearchClick,
                    scope: me
                }, {
                    xtype: 'button',
                    text: 'Limpiar',
                    handler: function ()
                    {
                        me.down('form').getForm().reset()
                    }
                }]
            }]
        }];
        me.keys = [
        {
            key: [Ext.EventObject.ENTER],
            handler: function ()
            {
                me.onSearchClick
            }
        }];
        me.callParent(arguments);
    },
    definirCamposBusqueda: function (store)
    {
        this.down('#filtroBusqueda').store = store;
        this.down('#filtroBusqueda2').store = store;
        this.down('#filtroBusqueda3').store = store;
    },
    // al hacer doble click sobre una fila
    onSearchClick: function (button)
    {
        var filtro = this.down('#filtroBusqueda').getValue();
		var comparador = this.down('#filtroComparador').getValue();
        var valor = this.down('#textoBusqueda').getValue();
        var filtro2 = this.down('#filtroBusqueda2').getValue();
		var comparador2 = this.down('#filtroComparador2').getValue();
        var valor2 = this.down('#textoBusqueda2').getValue();
        var operador2 = this.down('#filtroOperador2').getValue();
        var filtro3 = this.down('#filtroBusqueda3').getValue();
		var comparador3 = this.down('#filtroComparador3').getValue();
        var valor3 = this.down('#textoBusqueda3').getValue();
        var operador3 = this.down('#filtroOperador3').getValue();
        // le ponemos los filtros de búsqueda "operadores: 1 and, 2 and not, 3 or, 4 or not, 5 (, 6 ) "
        this.storeBusqueda.getProxy().extraParams.filtros = Ext.encode(
        {
            'filtros': [
            {
                'nombre': filtro,
                'valor': valor,
                'operador': '1',
				'comparador': comparador
            }, {
                'nombre': filtro2,
                'valor': valor2,
                'operador': operador2,
				'comparador': comparador2
            }, {
                'nombre': filtro3,
                'valor': valor3,
                'operador': operador3,
				'comparador': comparador3
            }]
        });
		
        // recargamos
        this.storeBusqueda.loadPage(1);
        // marcamos el botón de búsqueda para que el usuario sepa que hay un filtro de búsqueda activo
        this.botonBusqueda.toggle(true, true);
        this.close();
    }
});