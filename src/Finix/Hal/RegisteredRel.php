<?php

namespace Finix\Hal;

/**
 * The list of link relation types registered by the IANA Registry:
 * http://www.iana.org/assignments/link-relations/link-relations.xhtml
 * Last updated: 2015-01-21
 */
class RegisteredRel
{
    /**
     * Not part of the IANA Registry but a reserved
     * relation type in the HAL Specification for
     * the CURIE syntax.
     */
    const CURIES = 'curies';

    // To update from the Link Relation Types CSV file available in the IANA Registry:
    // 0) check that the return line format is UNIX (\n)
    // 1) remove the first line
    // 2) find \n+\s+ and replace by an empty space
    // 3) find all: (([^,-]+)(?:-([^,-]+))?(?:-([^,-]+))?(?:-([^,-]+))?),(?:"((?:""|[^"])+)"|([^,]+))?,(?:"((?:""|[^"])+)"|([^,]+))?,(?:"((?:""|[^"])+)"|([^,]+))?\n
    //    replace by: /**\n * Relation Name: $1\n * Description: $6$7\n * Reference: $8$9\n * Notes: $10$11\n */\nconst \U$2$3$4$5\E = '$1';\n\n
    // The masks:
    // - 1:            The whole relation name
    // - 2 to 5:    Potentially the 4 parts of the relation name without the dashes
    // - 6 or 7:    The Description
    // - 8 or 9:    The Reference
    // - 10 or 11:    The Notes
    // Tested in Notepad++.

    /**
     * Relation Name: about
     * Description: Refers to a resource that is the subject of the link's context.
     * Reference: [RFC6903], section 2
     * Notes:
     */
    const ABOUT = 'about';

    /**
     * Relation Name: alternate
     * Description: Refers to a substitute for this context
     * Reference: [http://www.w3.org/TR/html5/links.html#link-type-alternate]
     * Notes:
     */
    const ALTERNATE = 'alternate';

    /**
     * Relation Name: appendix
     * Description: Refers to an appendix.
     * Reference: [http://www.w3.org/TR/1999/REC-html401-19991224]
     * Notes:
     */
    const APPENDIX = 'appendix';

    /**
     * Relation Name: archives
     * Description: Refers to a collection of records, documents, or other materials of historical interest.
     * Reference: [http://www.w3.org/TR/2011/WD-html5-20110113/links.html#rel-archives]
     * Notes:
     */
    const ARCHIVES = 'archives';

    /**
     * Relation Name: author
     * Description: Refers to the context's author.
     * Reference: [http://www.w3.org/TR/html5/links.html#link-type-author]
     * Notes:
     */
    const AUTHOR = 'author';

    /**
     * Relation Name: bookmark
     * Description: Gives a permanent link to use for bookmarking purposes.
     * Reference: [http://www.w3.org/TR/html5/links.html#link-type-bookmark]
     * Notes:
     */
    const BOOKMARK = 'bookmark';

    /**
     * Relation Name: canonical
     * Description: Designates the preferred version of a resource (the IRI and its contents).
     * Reference: [RFC6596]
     * Notes:
     */
    const CANONICAL = 'canonical';

    /**
     * Relation Name: chapter
     * Description: Refers to a chapter in a collection of resources.
     * Reference: [http://www.w3.org/TR/1999/REC-html401-19991224]
     * Notes:
     */
    const CHAPTER = 'chapter';

    /**
     * Relation Name: collection
     * Description: The target IRI points to a resource which represents the collection resource for the context IRI.
     * Reference: [RFC6573]
     * Notes:
     */
    const COLLECTION = 'collection';

    /**
     * Relation Name: contents
     * Description: Refers to a table of contents.
     * Reference: [http://www.w3.org/TR/1999/REC-html401-19991224]
     * Notes:
     */
    const CONTENTS = 'contents';

    /**
     * Relation Name: copyright
     * Description: Refers to a copyright statement that applies to the link's context.
     * Reference: [http://www.w3.org/TR/1999/REC-html401-19991224]
     * Notes:
     */
    const COPYRIGHT = 'copyright';

    /**
     * Relation Name: create-form
     * Description: The target IRI points to a resource where a submission form can be obtained.
     * Reference: [RFC6861]
     * Notes:
     */
    const CREATEFORM = 'create-form';

    /**
     * Relation Name: current
     * Description: Refers to a resource containing the most recent item(s) in a collection of resources.
     * Reference: [RFC5005]
     * Notes:
     */
    const CURRENT = 'current';

    /**
     * Relation Name: derivedfrom
     * Description: The target IRI points to a resource from which this material was derived.
     * Reference: [draft-hoffman-xml2rfc]
     * Notes:
     */
    const DERIVEDFROM = 'derivedfrom';

    /**
     * Relation Name: describedby
     * Description: Refers to a resource providing information about the link's context.
     * Reference: [http://www.w3.org/TR/powder-dr/#assoc-linking]
     * Notes:
     */
    const DESCRIBEDBY = 'describedby';

    /**
     * Relation Name: describes
     * Description: The relationship A 'describes' B asserts that resource A provides a description of resource B. There are no constraints on the format or representation of either A or B, neither are there any further constraints on either resource.
     * Reference: [RFC6892]
     * Notes: This link relation type is the inverse of the 'describedby' relation type.  While 'describedby' establishes a relation from the described resource back to the resource that describes it, 'describes' established a relation from the describing resource to the resource it describes.  If B is 'describedby' A, then A 'describes' B.
     */
    const DESCRIBES = 'describes';

    /**
     * Relation Name: disclosure
     * Description: Refers to a list of patent disclosures made with respect to material for which 'disclosure' relation is specified.
     * Reference: [RFC6579]
     * Notes:
     */
    const DISCLOSURE = 'disclosure';

    /**
     * Relation Name: duplicate
     * Description: Refers to a resource whose available representations are byte-for-byte identical with the corresponding representations of the context IRI.
     * Reference: [RFC6249]
     * Notes: This relation is for static resources.  That is, an HTTP GET request on any duplicate will return the same representation.  It does not make sense for dynamic or POSTable resources and should not be used for them.
     */
    const DUPLICATE = 'duplicate';

    /**
     * Relation Name: edit
     * Description: Refers to a resource that can be used to edit the link's context.
     * Reference: [RFC5023]
     * Notes:
     */
    const EDIT = 'edit';

    /**
     * Relation Name: edit-form
     * Description: The target IRI points to a resource where a submission form for editing associated resource can be obtained.
     * Reference: [RFC6861]
     * Notes:
     */
    const EDITFORM = 'edit-form';

    /**
     * Relation Name: edit-media
     * Description: Refers to a resource that can be used to edit media associated with the link's context.
     * Reference: [RFC5023]
     * Notes:
     */
    const EDITMEDIA = 'edit-media';

    /**
     * Relation Name: enclosure
     * Description: Identifies a related resource that is potentially large and might require special handling.
     * Reference: [RFC4287]
     * Notes:
     */
    const ENCLOSURE = 'enclosure';

    /**
     * Relation Name: first
     * Description: An IRI that refers to the furthest preceding resource in a series of resources.
     * Reference: [RFC5988]
     * Notes: This relation type registration did not indicate a reference.  Originally requested by Mark Nottingham in December 2004.
     */
    const FIRST = 'first';

    /**
     * Relation Name: glossary
     * Description: Refers to a glossary of terms.
     * Reference: [http://www.w3.org/TR/1999/REC-html401-19991224]
     * Notes:
     */
    const GLOSSARY = 'glossary';

    /**
     * Relation Name: help
     * Description: Refers to context-sensitive help.
     * Reference: [http://www.w3.org/TR/html5/links.html#link-type-help]
     * Notes:
     */
    const HELP = 'help';

    /**
     * Relation Name: hosts
     * Description: Refers to a resource hosted by the server indicated by the link context.
     * Reference: [RFC6690]
     * Notes: This relation is used in CoRE where links are retrieved as a ""/.well-known/core"" resource representation, and is the default relation type in the CoRE Link Format.
     */
    const HOSTS = 'hosts';

    /**
     * Relation Name: hub
     * Description: Refers to a hub that enables registration for notification of updates to the context.
     * Reference: [http://pubsubhubbub.googlecode.com]
     * Notes: This relation type was requested by Brett Slatkin.
     */
    const HUB = 'hub';

    /**
     * Relation Name: icon
     * Description: Refers to an icon representing the link's context.
     * Reference: [http://www.w3.org/TR/html5/links.html#link-type-icon]
     * Notes:
     */
    const ICON = 'icon';

    /**
     * Relation Name: index
     * Description: Refers to an index.
     * Reference: [http://www.w3.org/TR/1999/REC-html401-19991224]
     * Notes:
     */
    const INDEX = 'index';

    /**
     * Relation Name: item
     * Description: The target IRI points to a resource that is a member of the collection represented by the context IRI.
     * Reference: [RFC6573]
     * Notes:
     */
    const ITEM = 'item';

    /**
     * Relation Name: last
     * Description: An IRI that refers to the furthest following resource in a series of resources.
     * Reference: [RFC5988]
     * Notes: This relation type registration did not indicate a reference. Originally requested by Mark Nottingham in December 2004.
     */
    const LAST = 'last';

    /**
     * Relation Name: latest-version
     * Description: Points to a resource containing the latest (e.g., current) version of the context.
     * Reference: [RFC5829]
     * Notes:
     */
    const LATESTVERSION = 'latest-version';

    /**
     * Relation Name: license
     * Description: Refers to a license associated with this context.
     * Reference: [RFC4946]
     * Notes: For implications of use in HTML, see:  http://www.w3.org/TR/html5/links.html#link-type-license
     */
    const LICENSE = 'license';

    /**
     * Relation Name: lrdd
     * Description: Refers to further information about the link's context, expressed as a LRDD (""Link-based Resource Descriptor Document"") resource.  See [RFC6415] for information about processing this relation type in host-meta documents. When used elsewhere, it refers to additional links and other metadata. Multiple instances indicate additional LRDD resources. LRDD resources MUST have an ""application/xrd+xml"" representation, and MAY have others.
     * Reference: [RFC6415]
     * Notes:
     */
    const LRDD = 'lrdd';

    /**
     * Relation Name: memento
     * Description: The Target IRI points to a Memento, a fixed resource that will not change state anymore.
     * Reference: [RFC7089]
     * Notes: A Memento for an Original Resource is a resource that encapsulates a prior state of the Original Resource.
     */
    const MEMENTO = 'memento';

    /**
     * Relation Name: monitor
     * Description: Refers to a resource that can be used to monitor changes in an HTTP resource.
     * Reference: [RFC5989]
     * Notes:
     */
    const MONITOR = 'monitor';

    /**
     * Relation Name: monitor-group
     * Description: Refers to a resource that can be used to monitor changes in a specified group of HTTP resources.
     * Reference: [RFC5989]
     * Notes:
     */
    const MONITORGROUP = 'monitor-group';

    /**
     * Relation Name: next
     * Description: Indicates that the link's context is a part of a series, and that the next in the series is the link target.
     * Reference: [http://www.w3.org/TR/html5/links.html#link-type-next]
     * Notes:
     */
    const NEXT = 'next';

    /**
     * Relation Name: next-archive
     * Description: Refers to the immediately following archive resource.
     * Reference: [RFC5005]
     * Notes:
     */
    const NEXTARCHIVE = 'next-archive';

    /**
     * Relation Name: nofollow
     * Description: Indicates that the context�s original author or publisher does not endorse the link target.
     * Reference: [http://www.w3.org/TR/html5/links.html#link-type-nofollow]
     * Notes:
     */
    const NOFOLLOW = 'nofollow';

    /**
     * Relation Name: noreferrer
     * Description: Indicates that no referrer information is to be leaked when following the link.
     * Reference: [http://www.w3.org/TR/html5/links.html#link-type-noreferrer]
     * Notes:
     */
    const NOREFERRER = 'noreferrer';

    /**
     * Relation Name: original
     * Description: The Target IRI points to an Original Resource.
     * Reference: [RFC7089]
     * Notes: An Original Resource is a resource that exists or used to exist, and for which access to one of its prior states may be required.
     */
    const ORIGINAL = 'original';

    /**
     * Relation Name: payment
     * Description: Indicates a resource where payment is accepted.
     * Reference: [RFC5988]
     * Notes: This relation type registration did not indicate a reference.  Requested by Joshua Kinberg and Robert Sayre.  It is meant as a general way to facilitate acts of payment, and thus this specification makes no assumptions on the type of payment or transaction protocol.  Examples may include a web page where donations are accepted or where goods and services are available for purchase. rel=""payment"" is not intended to initiate an automated transaction.  In Atom documents, a link element with a rel=""payment"" attribute may exist at the feed/channel level and/or the entry/item level.  For example, a rel=""payment"" link at the feed/channel level may point to a ""tip jar"" URI, whereas an entry/ item containing a book review may include a rel=""payment"" link that points to the location where the book may be purchased through an online retailer.
     */
    const PAYMENT = 'payment';

    /**
     * Relation Name: predecessor-version
     * Description: Points to a resource containing the predecessor version in the version history.
     * Reference: [RFC5829]
     * Notes:
     */
    const PREDECESSORVERSION = 'predecessor-version';

    /**
     * Relation Name: prefetch
     * Description: Indicates that the link target should be preemptively cached.
     * Reference: [http://www.w3.org/TR/html5/links.html#link-type-prefetch]
     * Notes:
     */
    const PREFETCH = 'prefetch';

    /**
     * Relation Name: prev
     * Description: Indicates that the link's context is a part of a series, and that the previous in the series is the link target.
     * Reference: [http://www.w3.org/TR/html5/links.html#link-type-prev]
     * Notes:
     */
    const PREV = 'prev';

    /**
     * Relation Name: preview
     * Description: Refers to a resource that provides a preview of the link's context.
     * Reference: [RFC6903], section 3
     * Notes:
     */
    const PREVIEW = 'preview';

    /**
     * Relation Name: previous
     * Description: Refers to the previous resource in an ordered series of resources.  Synonym for ""prev"".
     * Reference: [http://www.w3.org/TR/1999/REC-html401-19991224]
     * Notes:
     */
    const PREVIOUS = 'previous';

    /**
     * Relation Name: prev-archive
     * Description: Refers to the immediately preceding archive resource.
     * Reference: [RFC5005]
     * Notes:
     */
    const PREVARCHIVE = 'prev-archive';

    /**
     * Relation Name: privacy-policy
     * Description: Refers to a privacy policy associated with the link's context.
     * Reference: [RFC6903], section 4
     * Notes:
     */
    const PRIVACYPOLICY = 'privacy-policy';

    /**
     * Relation Name: profile
     * Description: Identifying that a resource representation conforms
     * to a certain profile, without affecting the non-profile semantics
     * of the resource representation.
     * Reference: [RFC6906]
     * Notes: Profile URIs are primarily intended to be used as
     * identifiers, and thus clients SHOULD NOT indiscriminately access
     * profile URIs.
     */
    const PROFILE = 'profile';

    /**
     * Relation Name: related
     * Description: Identifies a related resource.
     * Reference: [RFC4287]
     * Notes:
     */
    const RELATED = 'related';

    /**
     * Relation Name: replies
     * Description: Identifies a resource that is a reply to the context of the link.
     * Reference: [RFC4685]
     * Notes:
     */
    const REPLIES = 'replies';

    /**
     * Relation Name: search
     * Description: Refers to a resource that can be used to search through the link's context and related resources.
     * Reference: [http://www.opensearch.org/Specifications/OpenSearch/1.1]
     * Notes:
     */
    const SEARCH = 'search';

    /**
     * Relation Name: section
     * Description: Refers to a section in a collection of resources.
     * Reference: [http://www.w3.org/TR/1999/REC-html401-19991224]
     * Notes:
     */
    const SECTION = 'section';

    /**
     * Relation Name: self
     * Description: Conveys an identifier for the link's context.
     * Reference: [RFC4287]
     * Notes:
     */
    const SELF = 'self';

    /**
     * Relation Name: service
     * Description: Indicates a URI that can be used to retrieve a service document.
     * Reference: [RFC5023]
     * Notes: When used in an Atom document, this relation type specifies Atom Publishing Protocol service documents by default.  Requested by James Snell.
     */
    const SERVICE = 'service';

    /**
     * Relation Name: start
     * Description: Refers to the first resource in a collection of resources.
     * Reference: [http://www.w3.org/TR/1999/REC-html401-19991224]
     * Notes:
     */
    const START = 'start';

    /**
     * Relation Name: stylesheet
     * Description: Refers to a stylesheet.
     * Reference: [http://www.w3.org/TR/html5/links.html#link-type-stylesheet]
     * Notes:
     */
    const STYLESHEET = 'stylesheet';

    /**
     * Relation Name: subsection
     * Description: Refers to a resource serving as a subsection in a collection of resources.
     * Reference: [http://www.w3.org/TR/1999/REC-html401-19991224]
     * Notes:
     */
    const SUBSECTION = 'subsection';

    /**
     * Relation Name: successor-version
     * Description: Points to a resource containing the successor version in the version history.
     * Reference: [RFC5829]
     * Notes:
     */
    const SUCCESSORVERSION = 'successor-version';

    /**
     * Relation Name: tag
     * Description: Gives a tag (identified by the given address) that applies to the current document.
     * Reference: [http://www.w3.org/TR/html5/links.html#link-type-tag]
     * Notes:
     */
    const TAG = 'tag';

    /**
     * Relation Name: terms-of-service
     * Description: Refers to the terms of service associated with the link's context.
     * Reference: [RFC6903], section 5
     * Notes:
     */
    const TERMSOFSERVICE = 'terms-of-service';

    /**
     * Relation Name: timegate
     * Description: The Target IRI points to a TimeGate for an Original Resource.
     * Reference: [RFC7089]
     * Notes: A TimeGate for an Original Resource is a resource that is capable of datetime negotiation to support access to prior states of the Original Resource.
     */
    const TIMEGATE = 'timegate';

    /**
     * Relation Name: timemap
     * Description: The Target IRI points to a TimeMap for an Original Resource.
     * Reference: [RFC7089]
     * Notes: A TimeMap for an Original Resource is a resource from which a list of URIs of Mementos of the Original Resource is available.
     */
    const TIMEMAP = 'timemap';

    /**
     * Relation Name: type
     * Description: Refers to a resource identifying the abstract semantic type of which the link's context is considered to be an instance.
     * Reference: [RFC6903], section 6
     * Notes:
     */
    const TYPE = 'type';

    /**
     * Relation Name: up
     * Description: Refers to a parent document in a hierarchy of documents.
     * Reference: [RFC5988]
     * Notes: This relation type registration did not indicate a reference.  Requested by Noah Slater.
     */
    const UP = 'up';

    /**
     * Relation Name: version-history
     * Description: Points to a resource containing the version history for the context.
     * Reference: [RFC5829]
     * Notes:
     */
    const VERSIONHISTORY = 'version-history';

    /**
     * Relation Name: via
     * Description: Identifies a resource that is the source of the information in the link's context.
     * Reference: [RFC4287]
     * Notes:
     */
    const VIA = 'via';

    /**
     * Relation Name: working-copy
     * Description: Points to a working copy for this resource.
     * Reference: [RFC5829]
     * Notes:
     */
    const WORKINGCOPY = 'working-copy';

    /**
     * Relation Name: working-copy-of
     * Description: Points to the versioned resource from which this working copy was obtained.
     * Reference: [RFC5829]
     * Notes:
     */
    const WORKINGCOPYOF = 'working-copy-of';
}