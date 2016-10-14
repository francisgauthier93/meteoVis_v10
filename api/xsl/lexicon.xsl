<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    xmlns:rali="jsreal.rali.iro.umontreal.ca" 
    exclude-result-prefixes="rali"
    version="1.0">
    
    <xsl:output method="text"
        version="1.0"
        encoding="utf-8"
        omit-xml-declaration="yes"
        standalone="no"
        cdata-section-elements="namelist"
        indent="yes"
        media-type="application/json" />
    
    <!--<xsl:variable name="dictionary">
        <xsl:choose>
            <xsl:when test="contains(base-uri(), 'french')">
                <xsl:text>lexFr</xsl:text>
            </xsl:when>
            <xsl:otherwise>
                <xsl:text>lexEn</xsl:text>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:variable>-->
    
    <xsl:key name="base" match="word" use="base" />
    
    <rali:ts_category>
        <!-- Common -->
        <entry src="verb">V</entry>
        <entry src="determiner">D</entry>
        <entry src="noun">N</entry>
        <entry src="adjective">A</entry>
        <entry src="adverb">Adv</entry>
        <entry src="preposition">P</entry>
        <entry src="conjunction">C</entry>
        <entry src="pronoun">Pro</entry>
        
        <!-- French only -->
        <entry src="complementiser">C</entry>
        
        <!-- English only -->
        <entry src="modal">M</entry>
    </rali:ts_category>
    
    <rali:ts_tag>
        <!-- Common -->
        <entry src="present3s">p3</entry>
        <entry src="pastParticiple">pp</entry>
        <entry src="presentParticiple">pr</entry>
        <entry src="plural">p</entry>
        <entry src="comparative">co</entry>
        
        <!-- French only -->
        <entry src="present1s">p1</entry>
        <entry src="present2s">p2</entry>
        <entry src="present1p">p4</entry>
        <entry src="present2p">p5</entry>
        <entry src="present3p">p6</entry>
        <entry src="imperative2s">ip2</entry>
        <entry src="imperative1p">ip4</entry>
        <entry src="imperative2p">ip5</entry>
        <entry src="future_radical">fr</entry>
        <entry src="imparfait_radical">ir</entry>
        <entry src="subjunctive1s">s1</entry>
        <entry src="subjunctive2s">s2</entry>
        <entry src="subjunctive3s">s3</entry>
        <entry src="subjunctive1p">s4</entry>
        <entry src="subjunctive2p">s5</entry>
        <entry src="subjunctive3p">s6</entry>
        <entry src="feminine_singular">fs</entry>
        <entry src="feminine_plural">fp</entry>
        <entry src="feminine_past_participle">pf</entry>
        <entry src="gender">g</entry>
        <entry src="person">pe</entry>
        <entry src="pronoun_type">pt</entry>
        <entry src="liaison">l</entry>
        <entry src="particle">pa</entry>
        <entry src="number">n</entry>
        <entry src="discourse_function">df</entry>
        <entry src="opposite_gender">og</entry>
        <entry src="copular">cp</entry>
        <entry src="vowel_elision">ve</entry>
        <entry src="possessive">po</entry>
        <entry src="ne_only_negation">ne</entry>
        <entry src="detached">d</entry>
        <entry src="reflexive">r</entry>
        <entry src="no_comma">nc</entry>
        <entry src="repeated_conjunction">rc</entry>
        <entry src="auxiliary_etre">ae</entry>
        <entry src="aspired_h">h</entry>
        <entry src="clitic_rising">cr</entry>
        
        <!-- English only -->
        <entry src="ditransitive">di</entry>
        <entry src="transitive">t</entry>
        <entry src="predicative">pr</entry>
        <entry src="qualitative">q</entry>
        <entry src="intensifier">in</entry>
        <entry src="verbModifier">vm</entry>
        <entry src="verb_modifier">vm</entry>
        <entry src="classifying">cl</entry>
        <entry src="intransitive">it</entry>
        <entry src="nonCount">nco</entry>
        <entry src="sentence_modifier">sm</entry>
        <entry src="past">pas</entry>
        <entry src="superlative">sp</entry>
        <entry src="colour">col</entry>
        <entry src="proper">pro</entry>
        <entry src="irreg">irr</entry>
    </rali:ts_tag>
    
    <rali:ts_value>
        <!-- French only -->
        <entry src="masculine">m</entry>
        <entry src="feminine">f</entry>
        <entry src="neuter">n</entry>
        <entry src="first">1</entry>
        <entry src="second">2</entry>
        <entry src="third">3</entry>
        <entry src="personal">p</entry>
        <entry src="special_personal">s</entry>
        <entry src="demonstrative">d</entry>
        <entry src="indefinite">i</entry>
        <entry src="relative">r</entry>
        <entry src="singular">s</entry>
        <entry src="plural">p</entry>
        <entry src="subject">s</entry>
        <entry src="object">o</entry>
        <entry src="indirect_object">i</entry>
    </rali:ts_value>
    
    <!-- Every distinct tag and values -->
    <!--<xsl:template match="lexicon">
        <xsl:for-each-group select="word/*" group-by="name()">
            <xsl:text>
                
            </xsl:text>
            <xsl:value-of select="name()" />
            <xsl:text> : </xsl:text>
            <xsl:if test="current-group() != ''">
                <xsl:for-each select="distinct-values(current-group())">
                    <xsl:value-of select="."/><xsl:text>, </xsl:text>
                </xsl:for-each>
            </xsl:if>
        </xsl:for-each-group>
    </xsl:template>-->
    
    <xsl:template match="lexicon">
        <xsl:variable name="content">
            <xsl:apply-templates select="word[generate-id(.)=generate-id(key('base', base)[1])]" />
        </xsl:variable>
        
        <xsl:text>{</xsl:text>
        <xsl:value-of select="substring($content, 1, string-length($content)-10)" />
        <xsl:text>}</xsl:text>
    </xsl:template>
    
    <xsl:template match="word">
        <xsl:text>"</xsl:text>
            <xsl:value-of select="base"/>
        <xsl:text>": {</xsl:text>
        
        <xsl:variable name="values">
        <xsl:for-each select="key('base', base)/*">
            <xsl:variable name="tag" select="name(current())" />
            
            <xsl:variable name="next">
                <xsl:choose>
                    <xsl:when test="name(following-sibling::*[1]) != 'id'">
                        <xsl:value-of select="name(following-sibling::*[1])" />
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select="name(following-sibling::*[2])" />
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:variable>
            
            <xsl:choose>
                <xsl:when test="$tag = 'base'">
                </xsl:when>
                <xsl:when test="$tag = 'category'">
                    <xsl:call-template name="category">
                        <xsl:with-param name="position" select="position()" />
                        <xsl:with-param name="next" select="$next" />
                    </xsl:call-template>
                </xsl:when>
                <xsl:when test="$tag = 'id'">
                </xsl:when>
                <xsl:when test="$tag = 'preposed'">
                    <xsl:text>"pos": "pre"</xsl:text>
                    <xsl:if test="$next != 'base' and $next != ''">
                        <xsl:text>, </xsl:text>
                    </xsl:if>
                </xsl:when>
                <xsl:when test="count(document('')/*/rali:ts_tag/entry[@src=$tag]) > 0">
                    <xsl:text>"</xsl:text>
                    <xsl:value-of select="document('')/*/rali:ts_tag/entry[@src=$tag]"/>
                    <xsl:text>": </xsl:text>
                    <xsl:choose>
                        <xsl:when test="current() = ''">
                            <xsl:text>1</xsl:text>
                        </xsl:when>
                        <xsl:when test="count(document('')/*/rali:ts_value/entry[@src=current()]) > 0">
                            <xsl:variable name="value" select="document('')/*/rali:ts_value/entry[@src=current()]" />
                            <xsl:if test="$value='' or $value!=number($value)">
                                <xsl:text>"</xsl:text>
                            </xsl:if>
                            
                            <xsl:value-of select="$value"/>
                            
                            <xsl:if test="$value='' or $value!=number($value)">
                                <xsl:text>"</xsl:text>
                            </xsl:if>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:text>"</xsl:text>
                            <xsl:value-of select="current()" />
                            <xsl:text>"</xsl:text>
                        </xsl:otherwise>
                    </xsl:choose>
                    
                    <xsl:if test="$next != 'base' and $next != ''">
                        <xsl:text>, </xsl:text>
                    </xsl:if>
                    
                </xsl:when>
                <xsl:otherwise>
                    <xsl:message terminate="yes">
                        <xsl:text>ERROR: Missing tag : </xsl:text><xsl:value-of select="$tag" />
                    </xsl:message>
                </xsl:otherwise>
            </xsl:choose>
            
        </xsl:for-each>
        </xsl:variable>
        
        <xsl:value-of select="$values" />
        
        <xsl:text>}},
        </xsl:text>
               
    </xsl:template>
    
    <xsl:template name="category">
        <xsl:param name="position" />
        <xsl:param name="next"/>
        
        <xsl:if test="$position > 2">
            <xsl:text>},</xsl:text>
        </xsl:if>
        
        <xsl:choose>
            <xsl:when test="count(document('')/*/rali:ts_category/entry[@src=current()]) > 0">
                <xsl:text>"</xsl:text>
                <xsl:value-of select="document('')/*/rali:ts_category/entry[@src=current()]"/>
                <xsl:text>": {</xsl:text>
            </xsl:when>
            <xsl:otherwise>
                <xsl:message terminate="yes">
                    <xsl:text>ERROR: Missing category : </xsl:text><xsl:value-of select="." />
                </xsl:message>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    
</xsl:stylesheet>