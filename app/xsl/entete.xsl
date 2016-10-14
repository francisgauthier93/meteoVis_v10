<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">
    
    <xsl:output encoding="utf-8" method="html" media="text/html" 
            omit-xml-declaration="yes" indent="yes" />
    
<!--    <xsl:param name="fichieren" />
    <xsl:variable name="doc" select="document($fichieren)"></xsl:variable>
    <xsl:variable name="nomVilleen" select="$doc/siteData/location[1]/name[1]"/>
    <xsl:variable name="dateen" select="$doc/siteData/currentConditions[1]/dateTime[2]/textSummary[1]"></xsl:variable>-->
    <xsl:template match="siteData">
        <xsl:variable name="icone" select="/siteData/currentConditions[1]/iconCode[1]"></xsl:variable>
        <xsl:variable name="icone_extension" select="/siteData/currentConditions[1]/iconCode[1]/@format"></xsl:variable>
        <xsl:variable name="condition" select="/siteData/currentConditions[1]/condition[1]"></xsl:variable>
        <xsl:variable name="temp" select="/siteData/currentConditions[1]/temperature[1]"></xsl:variable>
        
        <!-- ***************************************** -->
        <!-- *      condition courante               * -->    
        <!-- ***************************************** -->
        
        <xsl:choose>
            <xsl:when test="$icone !=''">
                <div class="col-sm-1">
                    <img class="pull-left" src="public/img/icones_{$icone_extension}/{$icone}.{$icone_extension}" 
                         width="70px" title="{$condition}" alt="{$condition}"/>
                </div>
            </xsl:when>
            <xsl:otherwise>
                <div class="col-sm-1"></div>
            </xsl:otherwise>
        </xsl:choose>
        
        <div class="col-sm-2">
            <font color="#CDC9C9">
                <h1 class="celsius">
                    <xsl:value-of select="$temp"/>
                </h1> 
            </font>
        </div>
        <div class="col-sm-3">
            <h1 data-original-translation="">
<!--                <span class="fr">
                    <xsl:text>  </xsl:text>
                    <xsl:value-of select="/siteData/location[1]/name[1]"></xsl:value-of>
                </span>
                <span class="en">
                    <xsl:text>  </xsl:text>
                    <xsl:value-of select="$nomVilleen"></xsl:value-of>
                </span> -->
                <xsl:value-of select="/siteData/location[1]/name[1]"></xsl:value-of>
                <xsl:text>, </xsl:text>
                <xsl:value-of select="translate(/siteData/location[1]/province[1]/@code,
                            'abcdefghijklmnopqrstuvwxyz',
                            'ABCDEFGHIJKLMNOPQRSTUVWXYZ')"></xsl:value-of>
            </h1>
        </div>
        <div class="col-sm-4 h1">
            <small data-original-translation="">
                <xsl:value-of select="/siteData/currentConditions[1]/dateTime[2]/year"/>
                <xsl:text>-</xsl:text>
                <xsl:value-of select="/siteData/currentConditions[1]/dateTime[2]/month"/>
                <xsl:text>-</xsl:text>
                <xsl:value-of select="/siteData/currentConditions[1]/dateTime[2]/day"/>
                <xsl:text> </xsl:text>
                <xsl:value-of select="/siteData/currentConditions[1]/dateTime[2]/hour"/>
                <xsl:text>:</xsl:text>
                <xsl:value-of select="/siteData/currentConditions[1]/dateTime[2]/minute"/>
            </small>
        </div>
        <div class="col-sm-2">
            <button id="degres" type="button" class="btn btn-inverse btn-small">°F</button>
            <button id="lang" type="button" class="btn btn-info">en</button>                
        </div>
    </xsl:template>
</xsl:stylesheet>