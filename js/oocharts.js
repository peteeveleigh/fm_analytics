/*----------------------------------------------------------------
oomanager
----------------------------------------------------------------*/

var oo = oo || new ooManager();

function ooManager() {
    this.packages = [];
    this.versionMajor = "2";
    this.versionMinor = "1";
    this.version = "v" + this.versionMajor + "." + this.versionMinor;
    this.serviceEndpoint = "https://oocharts.org/s/api/v2?callback=?";
    this.ooid = undefined;

    this.load = ooManager_load;
    this.loadScript = ooManager_loadScript;
    this.setOOId = ooManager_setOOId;

    this.Timeline = ooTimeline;
    this.Metric = ooMetric;
    this.Pie = ooPie;
    this.Table = ooTable;
    this.Query = ooQuery;
}

function ooManager_loadScript(src, callback) {
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = src;
    s.async = false;

    s.onreadystatechange = s.onload = function () {

        var state = s.readyState;

        if (!callback.done && (!state || /loaded|complete/.test(state))) {
            callback.done = true;
            callback();
        }
    };

    var c = document.getElementsByTagName('script')[0];
    c.parentNode.insertBefore(s, c);
}

function ooManager_setOOId(ooid) {
    this.ooid = ooid;
}

function ooManager_load(callback) {
    var m = this;

    var load_jsapi = function (callback) {
        if (typeof google === 'undefined') {
            m.loadScript("https://www.google.com/jsapi", callback);
        }
        else { callback(); }
    }

    var load_jquery = function (callback) {
        if (typeof jQuery === 'undefined') {
            m.loadScript("https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js", callback);
        }
        else { callback(); }
    }

    var load_visualization = function (callback) {
        if (typeof google.visualization === 'undefined') {
            google.load("visualization", "1", { packages: ['corechart', 'table'], 'callback': callback });
        }
        else {
            var necpac = [];

            if (typeof google.visualization.corechart === 'undefined') {
                necpac.push('corechart');
            }

            if (typeof google.visualization.table === 'undefined') {
                necpac.push('table');
            }

            if (necpac.length > 0) {
                google.load("visualization", "1", { packages: necpac, 'callback': callback });
            }
        }
    }

    var cb = callback;

    load_jsapi(function () {
        load_jquery(function () {
            load_visualization(function () {
                cb();
            });
        })
    });
}

/*----------------------------------------------------------------
Metric
----------------------------------------------------------------*/

function ooMetric(aid, startDate, endDate) {
    this.query = new ooQuery(aid, startDate, endDate);
    this.metric = undefined;

    this.draw = ooMetric_draw;
    this.setMetric = ooMetric_setMetric;
}

function ooMetric_setMetric(metric) {
    this.metric = metric;
}

function ooMetric_draw(container, callback) {

    this.query.addMetric(this.metric);

    this.query.execute(function (data) {
        $("#" + container).text(data[0][0]);

        if (typeof callback != 'undefined') {
            callback();
        }
    });
}

/*----------------------------------------------------------------
Timeline
----------------------------------------------------------------*/

function ooTimeline(aid, startDate, endDate) {
    this.query = new ooQuery(aid, startDate, endDate);
    this.query.addDimension("ga:date");

    this.options = {};
    this.labels = [];

    this.draw = ooTimeline_draw;
    this.addMetric = ooTimeline_addMetric;
    this.setOption = oo_setOption;
}

function ooTimeline_addMetric(metric, label) {
    this.query.addMetric(metric);
    this.labels.push(label);
}

function ooTimeline_draw(container, callback) {
    var t = this;

    this.query.execute(function (data) {

        //Turn analytics date strings into date
        for (var r in data) {
            data[r][0] = new Date(data[r][0].substring(0, 4), data[r][0].substring(4, 6) - 1, data[r][0].substring(6, 8));
        }

        var dt = new google.visualization.DataTable();

        dt.addColumn('date', 'Date');

        for (var l in t.labels) {
            dt.addColumn('number', t.labels[l]);
        }

        dt.addRows(data);

        var chart = new google.visualization.LineChart(document.getElementById(container));
        chart.draw(dt, t.options);

        if (typeof callback != 'undefined') {
            callback();
        }
    });
}

/*----------------------------------------------------------------
Pie
----------------------------------------------------------------*/

function ooPie(aid, startDate, endDate) {
    this.query = new ooQuery(aid, startDate, endDate);

    this.metLabel = undefined;
    this.dimension = undefined;
    this.metric = undefined;
    this.options = {};

    this.draw = ooPie_draw;
    this.setOption = oo_setOption;
    this.setMetric = ooPie_setMetric;
    this.setDimension = ooPie_setDimension;
}

function ooPie_setMetric(metric, label) {
    this.metric = metric;
    this.metLabel = label;
}

function ooPie_setDimension(dimension) {
    this.dimension = dimension;
}

function ooPie_draw(container, callback) {
    this.query.addMetric(this.metric);
    this.query.addDimension(this.dimension);

    var t = this;

    this.query.execute(function (data) {

        var dt = new google.visualization.DataTable();

        dt.addColumn('string', t.dimension);
        dt.addColumn('number', t.metLabel);
        dt.addRows(data);

        var chart = new google.visualization.PieChart(document.getElementById(container));
        chart.draw(dt, t.options);

        if (typeof callback != 'undefined') {
            callback();
        }
    });
}


/*----------------------------------------------------------------
Table
----------------------------------------------------------------*/

function ooTable(aid, startDate, endDate) {
    this.query = new ooQuery(aid, startDate, endDate);

    this.dimLabels = [];
    this.metLabels = [];
    this.options = {};

    this.draw = ooTable_draw;
    this.setOption = oo_setOption;
    this.addMetric = ooTable_addMetric;
    this.addDimension = ooTable_addDimension;
}

function ooTable_addDimension(dim, label) {
    this.query.addDimension(dim);
    this.dimLabels.push(label);
}

function ooTable_addMetric(metric, label) {
    this.query.addMetric(metric);
    this.metLabels.push(label);
}

function ooTable_draw(container, callback) {
    var t = this;

    this.query.execute(function (data) {

        var labelRow = [];

        for (var d in t.dimLabels) {
            labelRow.push(t.dimLabels[d]);
        }

        for (var m in t.metLabels) {
            labelRow.push(t.metLabels[m]);
        }

        data.splice(0, 0, labelRow);

        var dt = google.visualization.arrayToDataTable(data);

        var chart = new google.visualization.Table(document.getElementById(container));
        chart.draw(dt, t.options);

        if (typeof callback != 'undefined') {
            callback();
        }
    });
}

/*----------------------------------------------------------------
Query
----------------------------------------------------------------*/

function ooQuery(aid, startDate, endDate) {
    this.metrics = [];
    this.dimensions = [];
    this.segments = undefined;
    this.filters = undefined;
    this.sort = undefined;
    this.maxResults = undefined;
    this.autoParse = true;
    this.startDate = startDate;
    this.endDate = endDate;
    this.aid = aid;

    this.addMetric = ooQuery_addMetric;
    this.addDimension = ooQuery_addDimension;
    this.setFilter = ooQuery_setFilter;
    this.setSort = ooQuery_setSort;
    this.setSegment = ooQuery_setSegment;
    this.setMaxResults = ooQuery_setMaxResults;
    this.setAutoParse = ooQuery_setAutoParse;
    this.execute = ooQuery_execute;
}

function ooQuery_execute(callback) {
    try {
        var loadUrl = oo.serviceEndpoint;

        var parameters =
        {
            id: oo.ooid,
            aid: this.aid,
            startDate: this.startDate.toDateString(),
            endDate: this.endDate.toDateString(),
            versionMinor: oo.versionMinor,
            autoParse: this.autoParse
        };

        if(this.metrics.length > 0)
        {
            parameters['metrics'] = this.metrics.toString();
        }

        if(this.dimensions.length > 0)
        {
            parameters['dimensions'] = this.dimensions.toString();
        }

        if(typeof this.segments != 'undefined')
        {
            parameters['segments'] = this.segments;
        }

        if(typeof this.filters != 'undefined')
        {
            parameters['filters'] = this.filters;
        }

        if(typeof this.sort != 'undefined')
        {
            parameters['sort'] = this.sort;
        }

        if (typeof this.maxResults != 'undefined') {
            parameters['maxResults'] = this.maxResults;
        }

        $.getJSON(loadUrl, parameters, function (response) {
            callback(response.data);
        })
        .error(function () { throw Error("Data could not be retrieved from service."); });
    }
    catch (err) {
        throw new Error("Error making request: " + err.message);
    }
}

function ooQuery_addMetric(metric) {
    this.metrics.push(metric);
}

function ooQuery_addDimension(dimension) {
    this.dimensions.push(dimension);
}

function ooQuery_setFilter(filters) {
    this.filters = filters;
}

function ooQuery_setSort(sort) {
    this.sort = sort;
}

function ooQuery_setSegment(segment) {
    this.segments = segment;
}

function ooQuery_setMaxResults(max) {
    this.maxResults = max;
}

function ooQuery_setAutoParse(val) {
    this.autoParse = val;
}


/*----------------------------------------------------------------
oo Generic Functions
----------------------------------------------------------------*/

function oo_setOption(option, value) {
    if (typeof this.options[option] == 'undefined') {
        this.options[option] = value;
    }
    else {
        var o = this;

        $.extend(o.options[option], value);
    }
}