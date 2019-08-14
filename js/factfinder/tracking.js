/**
 * FACTFinder_Tracking
 *
 * @category Mage
 * @package FACTFinder_Tracking
 * @author Flagbit Magento Team <magento@flagbit.de>
 * @copyright Copyright (c) 2016 Flagbit GmbH & Co. KG
 * @license https://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link http://www.flagbit.de
 *
 */

var FactfinderTracking = Class.create({
    url:  null,
    data: null,
    classname: null,
    mapping: null,
    request: null,
    regex: new RegExp(/product\/([0-9]+)\//),
    asynchronous: true,
    initialize: function(classname, data, mapping, url, asynchronous) {
        this.classname = classname;
        this.data = data;
        this.mapping = mapping;
        this.url = url;
        if(typeof asynchronous !== 'undefined')
            this.asynchronous = asynchronous;
    },

    init: function() {
        $$(this.classname+' a',this.classname+' button').each(function(element) {
            this.mapping.each(function(pair, index) {
                if(element.readAttribute('href') && element.readAttribute('href').indexOf(pair.key) >= 0){
                    return this.prepareElement(element, pair.value, 'click');
                }
                if(element.readAttribute('onclick') && element.readAttribute('onclick').indexOf(pair.key) >= 0){
                    return this.prepareElement(element, pair.value, 'click');
                }
            }.bind(this));

            if(element.readAttribute('href')) {
                var match = this.regex.exec(element.readAttribute('href'));
                if(match && match[1] && !this.irrelevantLink(element.readAttribute('class'))) {
                    return this.prepareElement(element, match[1], 'click');
                }
            }

            if(element.readAttribute('onclick')) {
                var match = this.regex.exec(element.readAttribute('onclick'));
                if(match && match[1] && !this.irrelevantLink(element.readAttribute('class'))) {
                    return this.prepareElement(element, match[1], 'click');
                }
            }
        }.bind(this));
    },

    prepareElement: function(element, id, eventType) {
        Event.observe(element, 'click', function(event) {
            this.recordRequest(id, eventType);
        }.bind(this));
    },

    recordRequest: function(id, eventType) {

        var data = this.data.get(id);

        this.request = new Ajax.Request(this.url, {
            asynchronous: this.asynchronous,
            method: 'post',
            parameters: data
        });
        return false;
    },
    
    irrelevantLink: function (classAttribute) {
        if (classAttribute != null && (classAttribute.indexOf('link-wishlist') != -1 || classAttribute.indexOf('link-compare') != -1)) {
            return true;
        }
        return false;
    }
});
