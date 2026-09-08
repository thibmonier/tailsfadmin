import { Controller } from "@hotwired/stimulus";
import ApexCharts from "apexcharts";

/**
 * Contrôleur Stimulus apexcharts — tailsfadmin--apexcharts
 *
 * US-018 : encapsule ApexCharts v7 (vendoré via importmap) sans CDN.
 * Pattern wrapper ADR-004 : lib JS tierce gérée dans connect()/disconnect().
 *
 * Valeurs (data-*-value) :
 *   - type    : "line" | "bar" | "area" | "radialBar" (défaut "line")
 *   - series  : JSON — tableau de séries ApexCharts
 *   - options : JSON — options supplémentaires (fusionnées avec les défauts)
 *   - height  : nombre de pixels (défaut 310)
 *
 * Dark-mode : MutationObserver sur la classe `.dark` de <html>
 *   → appelle this._chart.updateOptions({ theme }) sans re-render complet.
 *
 * Cycle de vie :
 *   connect()    → instancie ApexCharts, rend le graphe, observe le thème
 *   disconnect() → déconnecte l'observer, détruit l'instance (pas de fuite)
 */

/** Couleurs brand alignées sur les tokens TailAdmin. */
const BRAND_COLORS = ["#465FFF", "#9CB9FF", "#0EA5E9", "#22C55E", "#F59E0B"];

/** Construit les options par défaut selon le type de graphe. */
function defaultOptions(type, series, height) {
    const base = {
        chart: {
            fontFamily: "Outfit, sans-serif",
            type:       type,
            height:     height,
            toolbar:    { show: false },
            background: "transparent",
        },
        colors:      BRAND_COLORS,
        series:      series,
        dataLabels:  { enabled: false },
        grid: {
            borderColor: "#E4E7EC",
            strokeDashArray: 0,
            xaxis: { lines: { show: false } },
            yaxis: { lines: { show: true } },
        },
        stroke:  { curve: "straight", width: 2 },
        markers: { size: 0 },
        legend: {
            show:            true,
            position:        "top",
            horizontalAlign: "left",
            fontFamily:      "Outfit",
            markers:         { radius: 99 },
        },
        xaxis: {
            axisBorder: { show: false },
            axisTicks:  { show: false },
        },
        tooltip: { theme: "light" },
        theme:   { mode: "light" },
    };

    if (type === "bar") {
        base.plotOptions = {
            bar: {
                horizontal:              false,
                columnWidth:             "39%",
                borderRadius:            5,
                borderRadiusApplication: "end",
            },
        };
        base.stroke = { show: true, width: 4, colors: ["transparent"] };
    }

    if (type === "area") {
        base.fill = {
            type: "gradient",
            gradient: { opacityFrom: 0.55, opacityTo: 0 },
        };
        base.stroke = { curve: "straight", width: 2 };
    }

    return base;
}

/** Fusionne récursivement deux objets (right écrase left). */
function deepMerge(left, right) {
    const result = Object.assign({}, left);
    for (const key of Object.keys(right)) {
        if (
            right[key] !== null &&
            typeof right[key] === "object" &&
            !Array.isArray(right[key]) &&
            typeof left[key] === "object" &&
            left[key] !== null &&
            !Array.isArray(left[key])
        ) {
            result[key] = deepMerge(left[key], right[key]);
        } else {
            result[key] = right[key];
        }
    }
    return result;
}

export default class extends Controller {
    static values = {
        type:    { type: String,  default: "line" },
        series:  { type: String,  default: "[]" },
        options: { type: String,  default: "{}" },
        height:  { type: Number,  default: 310 },
    };

    connect() {
        let series  = [];
        let extra   = {};

        try {
            series = JSON.parse(this.seriesValue);
        } catch (e) {
            console.error("tailsfadmin--apexcharts: series JSON invalide", e);
        }

        try {
            extra = JSON.parse(this.optionsValue);
        } catch (e) {
            console.error("tailsfadmin--apexcharts: options JSON invalide", e);
        }

        const opts  = deepMerge(
            defaultOptions(this.typeValue, series, this.heightValue),
            extra,
        );

        // Thème initial
        const dark = document.documentElement.classList.contains("dark");
        opts.theme = { mode: dark ? "dark" : "light" };
        if (dark) {
            opts.grid.borderColor = "#374151";
        }

        this._chart = new ApexCharts(this.element, opts);
        this._chart.render();

        // Observer le changement de classe .dark sur <html>
        this._observer = new MutationObserver(() => {
            const isDark = document.documentElement.classList.contains("dark");
            this._chart.updateOptions({
                theme:  { mode: isDark ? "dark" : "light" },
                grid:   { borderColor: isDark ? "#374151" : "#E4E7EC" },
                tooltip: { theme: isDark ? "dark" : "light" },
            });
        });

        this._observer.observe(document.documentElement, {
            attributes:      true,
            attributeFilter: ["class"],
        });
    }

    disconnect() {
        if (this._observer) {
            this._observer.disconnect();
            this._observer = null;
        }
        if (this._chart) {
            this._chart.destroy();
            this._chart = null;
        }
    }
}
