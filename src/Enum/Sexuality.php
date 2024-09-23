<?php

namespace App\Enum;

enum Sexuality: string
{
    /**
     * Abrosexual describes someone whose sexuality is fluid or changeable. For example one day they may identify as
     * asexual, the next as lesbian, and the next as pansexual. Abrosexual people can fluctuate between all
     * sexualities, or just a few. The timing between fluctuations can also vary.
     */
    case Abrosexual = 'abrosexual';

    /**
     * Androphilic, or androsexual, describes someone who is primarily attracted to masculinity, be this sexually,
     * romantically, or aesthetically. These terms are particularly useful for people who identify outside of the
     * gender binary as unlike terms like gay or lesbian, they refer only to the presentation of the person someone
     * is attracted to and not the person themselves. For example, both heterosexual women and homosexual men can be
     * referred to as androphilic or androsexual.
     */
    case Androsexual = 'androsexual';

    /**
     * Aroace is short for Aromantic Asexual, which means someone who experiences little or no romantic and sexual
     * attraction.
     */
    case Aroace = 'aroace';

    /**
     * An aroflux person is someone whose romantic orientation fluctuates but generally stays on the aromantic
     * spectrum. They may feel strongly aromantic one day, and less so on another day.
     */
    case Aroflux = 'aroflux';

    /**
     * Aromantic refers to someone who does not experience romantic attraction. They may experience sexual and/or
     * aesthetic attraction, but not romantic attraction.
     */
    case Aromantic = 'aromantic';

    /**
     * An asexual person is someone who does not experience sexual attraction. They may experience romantic and/or
     * aesthetic attraction, but not sexual attraction.
     */
    case Asexual = 'asexual';

    /**
     * Bisexuality refers to sexual attraction to more than one gender and is inclusive of ALL genders, therefore bi
     * folk can be attracted to people from right across the gender spectrum.
     */
    case Bisexual = 'bisexual';

    /**
     * Demisexual refers to someone who only experiences sexual attraction to someone once they have created a
     * romantic or platonic relationship with them.
     */
    case Demisexual = 'demisexual';

    /**
     * Similarly to Demisexual, Demiromantic refers to someone who does not experience romantic attraction until
     * they have formed a strong connection with a partner.
     */
    case Demiromantic = 'demiromatic';

    /**
     * The exclusive attraction to those who are feminine in nature (FIN). This means finsexual people are attracted
     * to women, feminine aligned and/or feminine presenting non-binary people, and potentially feminine men.
     */
    case Finsexual = 'finsexual';

    /**
     * Someone who is attracted to the same gender as themselves. The rainbow flag is also used to represent the
     * LGBTQIA+ community as a whole.
     */
    case Homosexual = 'homosexual';

    /**
     * Vincian is a term used to describe gay men or men-aligned people. Often used as the masculine equivalent of
     * lesbian.
     */
    case Vincian = 'vincian';

    /**
     * Greysexual refers to people who experience limited sexual attraction. They experience sexual attraction very
     * rarely, or with very low intensity. Also known as gray-ace.
     */
    case Greysexual = 'greysexual';

    /**
     * Someone who is primarily romantically, sexually, or aesthetically attracted to femininity.
     */
    case Gynesexual = 'gynesexual';

    /**
     * Most often refers to women who are solely attracted to other women however some non binary people can also
     * identify as lesbian.
     */
    case Lesbian = 'lesbian';

    /**
     * Lithosexual refers to a person who may experience sexual attraction but does not want it reciprocated. Also
     * known as Akoisexual.
     */
    case Lithosexual = 'lithosexual';

    /**
     * A lithromantic person is someone who may experience romantic attraction but does not want it reciprocated.
     * Also known as Akoiromantic.
     */
    case Lithromantic = 'lithromantic';

    /**
     * Minsexual refers to the exclusive attraction to those who are masculine in nature (MIN). This can include
     * men, masculine aligned and/or masculine presenting non-binary people, and potentially masculine women.
     */
    case Minsexual = 'minsexual';

    /**
     * Multisexual is an umbrella term for any form of attraction to more than one gender. Also known as
     * Plurisexuality.
     */
    case Multisexual = 'multisexual';

    /**
     * Neptunic refers to the attraction to women, feminine non-binary people, and neutral non-binary people. It can
     * also be described as attraction to all except men and/or masculine-aligned non-binary people.
     */
    case Neptunic = 'neptunic';

    /**
     * Ninsexual refers to the exclusive attraction to those who are non-binary in nature (NIN). This includes
     * people who are non-binary, neutrois, androgyne, agender, and anyone whose gender or presentation is androgynous.
     */
    case Ninsexual = 'ninsexual';

    /**
     * Omnisexual refers to a person who is attracted to all genders or any gender, while still having a preference.
     */
    case Omnisexual = 'omnisexual';

    /**
     * Someone who is attracted to all genders, the pre-fix 'pan' meaning 'all' in Latin. Many Pansexual people also
     * describe themselves as being attracted to others based on their personality, not gender.
     */
    case Pansexual = 'pansexual';

    /**
     * Someone who is, or desires to be in, a consensual relationship with multiple partners. Polyamory is the opposite of monogamy.
     */
    case Polyamorous = 'polyamorous';

    /**
     * Polysexual refers to someone who is attracted to multiple genders, but not all genders.
     */
    case Polysexual = 'polysexual';

    /**
     * An umbrella term for someone whose sexuality and/or gender is not heterosexual, cisgender and/or allosexual.
     * Queer has been used as a term of abuse against the LGBTQ+ community, but is now often used by members of the
     * LGBTQ+ who have reclaimed it.
     */
    case Queer = 'queer';

    /**
     * Sapphic refers to a woman or woman-aligned person who is attracted to other women or woman-aligned people.
     * Also known as woman loving woman (WLW).
     */
    case Sapphic = 'sapphic';

    /**
     * The attraction to androgynous-aligned non-binary people. Mostly used by non-binary people to describe their
     * attraction without relying on the gender binary.
     */
    case Saturnic = 'saturnic';

    /**
     * Uranic refers to the attraction to men, masculine non-binary people, and neutral non-binary people. Can also
     * be described as attraction to all except women and/or feminine-aligned non-binary people.
     */
    case Uranic = 'uranic';
}