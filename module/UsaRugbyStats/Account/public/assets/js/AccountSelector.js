$.extend(true, USARugbyStats, {
    Account: {
        
    }
})


USARugbyStats.Account.Selector = function(cfg) 
{
    var config = {
        endpoint: '/api/account',
        endpointParameter: 'q',
        target: null,
        defaultValues: []
    };
    $.extend(true, config, cfg);
    
    var selector = '*[name=' + config.target.replace(/(\[|\])/g, "\\$1") + ']';
    
    $(selector).select2({
        placeholder: "Search for an account...",
        minimumInputLength: 2,
        maximumSelectionSize: 1,
        ajax: {
            url: config.endpoint,
            dataType: 'json',
            data: function (term, page) {
                return { q: term };
            },
            results: function (data, page) {
                return {results: data};
            }
        },
        formatSelection: function(item) {
            return item.display_name;
        },
        formatResult: function(item) {
            return item.display_name;
        },
        initSelection: function(element, callback) {
            callback(config.defaultValues[0]);
        }
    });
}