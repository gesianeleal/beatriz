/**
 * backbone model definitions for BEATRIZ
 */

/**
 * Use emulated HTTP if the server doesn't support PUT/DELETE or application/json requests
 */
Backbone.emulateHTTP = false;
Backbone.emulateJSON = false;

var model = {};

/**
 * long polling duration in miliseconds.  (5000 = recommended, 0 = disabled)
 * warning: setting this to a low number will increase server load
 */
model.longPollDuration = 0;

/**
 * whether to refresh the collection immediately after a model is updated
 */
model.reloadCollectionOnModelUpdate = true;


/**
 * a default sort method for sorting collection items.  this will sort the collection
 * based on the orderBy and orderDesc property that was used on the last fetch call
 * to the server. 
 */
model.AbstractCollection = Backbone.Collection.extend({
	totalResults: 0,
	totalPages: 0,
	currentPage: 0,
	pageSize: 0,
	orderBy: '',
	orderDesc: false,
	lastResponseText: null,
	lastRequestParams: null,
	collectionHasChanged: true,
	
	/**
	 * fetch the collection from the server using the same options and 
	 * parameters as the previous fetch
	 */
	refetch: function() {
		this.fetch({ data: this.lastRequestParams })
	},
	
	/* uncomment to debug fetch event triggers
	fetch: function(options) {
            this.constructor.__super__.fetch.apply(this, arguments);
	},
	// */
	
	/**
	 * client-side sorting baesd on the orderBy and orderDesc parameters that
	 * were used to fetch the data from the server.  Backbone ignores the
	 * order of records coming from the server so we have to sort them ourselves
	 */
	comparator: function(a,b) {
		
		var result = 0;
		var options = this.lastRequestParams;
		
		if (options && options.orderBy) {
			
			// lcase the first letter of the property name
			var propName = options.orderBy.charAt(0).toLowerCase() + options.orderBy.slice(1);
			var aVal = a.get(propName);
			var bVal = b.get(propName);
			
			if (isNaN(aVal) || isNaN(bVal)) {
				// treat comparison as case-insensitive strings
				aVal = aVal ? aVal.toLowerCase() : '';
				bVal = bVal ? bVal.toLowerCase() : '';
			} else {
				// treat comparision as a number
				aVal = Number(aVal);
				bVal = Number(bVal);
			}
			
			if (aVal < bVal) {
				result = options.orderDesc ? 1 : -1;
			} else if (aVal > bVal) {
				result = options.orderDesc ? -1 : 1;
			}
		}
		
		return result;

	},
	/**
	 * override parse to track changes and handle pagination
	 * if the server call has returned page data
	 */
	parse: function(response, options) {

		// the response is already decoded into object form, but it's easier to
		// compary the stringified version.  some earlier versions of backbone did
		// not include the raw response so there is some legacy support here
		var responseText = options && options.xhr ? options.xhr.responseText : JSON.stringify(response);
		this.collectionHasChanged = (this.lastResponseText != responseText);
		this.lastRequestParams = options ? options.data : undefined;
		
		// if the collection has changed then we need to force a re-sort because backbone will
		// only resort the data if a property in the model has changed
		if (this.lastResponseText && this.collectionHasChanged) this.sort({ silent:true });
		
		this.lastResponseText = responseText;
		
		var rows;

		if (response.currentPage) {
			rows = response.rows;
			this.totalResults = response.totalResults;
			this.totalPages = response.totalPages;
			this.currentPage = response.currentPage;
			this.pageSize = response.pageSize;
			this.orderBy = response.orderBy;
			this.orderDesc = response.orderDesc;
		} else {
			rows = response;
			this.totalResults = rows.length;
			this.totalPages = 1;
			this.currentPage = 1;
			this.pageSize = this.totalResults;
			this.orderBy = response.orderBy;
			this.orderDesc = response.orderDesc;
		}

		return rows;
	}
});

/**
 * Checklistvistoria Backbone Model
 */
model.ChecklistvistoriaModel = Backbone.Model.extend({
	urlRoot: 'api/checklistvistoria',
	idAttribute: 'id',
	id: '',
	vistoriaid: '',
	questaoid: '',
	tipoavaliacao: '',
	observacao: '',
	defaults: {
		'id': null,
		'vistoriaid': '',
		'questaoid': '',
		'tipoavaliacao': '',
		'observacao': ''
	}
});

/**
 * Checklistvistoria Backbone Collection
 */
model.ChecklistvistoriaCollection = model.AbstractCollection.extend({
	url: 'api/checklistvistorias',
	model: model.ChecklistvistoriaModel
});

/**
 * Engenheiro Backbone Model
 */
model.EngenheiroModel = Backbone.Model.extend({
	urlRoot: 'api/engenheiro',
	idAttribute: 'id',
	id: '',
	nome: '',
	defaults: {
		'id': null,
		'nome': ''
	}
});

/**
 * Engenheiro Backbone Collection
 */
model.EngenheiroCollection = model.AbstractCollection.extend({
	url: 'api/engenheiros',
	model: model.EngenheiroModel
});

/**
 * Grupoquestao Backbone Model
 */
model.GrupoquestaoModel = Backbone.Model.extend({
	urlRoot: 'api/grupoquestao',
	idAttribute: 'id',
	id: '',
	descricao: '',
	defaults: {
		'id': null,
		'descricao': ''
	}
});

/**
 * Grupoquestao Backbone Collection
 */
model.GrupoquestaoCollection = model.AbstractCollection.extend({
	url: 'api/grupoquestaos',
	model: model.GrupoquestaoModel
});

/**
 * Obra Backbone Model
 */
model.ObraModel = Backbone.Model.extend({
	urlRoot: 'api/obra',
	idAttribute: 'id',
	id: '',
	local: '',
	endereco: '',
	bairro: '',
	municipio: '',
	defaults: {
		'id': null,
		'local': '',
		'endereco': '',
		'bairro': '',
		'municipio': ''
	}
});

/**
 * Obra Backbone Collection
 */
model.ObraCollection = model.AbstractCollection.extend({
	url: 'api/obras',
	model: model.ObraModel
});

/**
 * Questao Backbone Model
 */
model.QuestaoModel = Backbone.Model.extend({
	urlRoot: 'api/questao',
	idAttribute: 'id',
	id: '',
	descricao: '',
	grupoquestaoid: '',
	GrupoquestaoDescricao: '',
        defaults: {
		'id': null,
		'descricao': '',
		'grupoquestaoid': ''
	}
});

/**
 * Questao Backbone Collection
 */
model.QuestaoCollection = model.AbstractCollection.extend({
	url: 'api/questaos',
	model: model.QuestaoModel
});

/**
 * Vistoria Backbone Model
 */
model.VistoriaModel = Backbone.Model.extend({
	urlRoot: 'api/vistoria',
	idAttribute: 'id',
	id: '',
	data: '',
	obraid: '',
        obraNome: '',
	engenheiroid: '',
	engenheiroNome: '',
	defaults: {
		'id': null,
		'data': new Date(),
		'obraid': '',
		'engenheiroid': ''
	}
});
/**
 * CheckList Backbone Model
 */
model.CheckListModel = Backbone.Model.extend({
	urlRoot: 'api/checklist',
	idAttribute: 'id',
	id: '',
	vistoriaid: '',
	questaoid: '',
	questaoDescricao: '',
        tipoavaliacao: '',
	observacao: '',
	defaults: {
		'id': null,
		'vistoriaid': '',
		'questaoid': '',
		'tipoavaliacao': '',
		'observacao': ''
	}
});

/**
 * CheckList Backbone Collection
 */
model.CheckListCollection = model.AbstractCollection.extend({
	url: 'api/checklists',
	model: model.CheckListModel
});

/**
 * Vistoria Backbone Collection
 */
model.VistoriaCollection = model.AbstractCollection.extend({
	url: 'api/vistorias',
	model: model.VistoriaModel
});

