import { Controller } from "@hotwired/stimulus";
import Dropzone from "dropzone";
import "dropzone/dist/dropzone.css";

/**
 * Contrôleur Stimulus dropzone — tailsfadmin--dropzone
 *
 * US-016 : encapsule Dropzone v6 (vendoré via importmap) sans CDN.
 * Pattern wrapper ADR-004 : lib JS tierce gérée dans connect()/disconnect().
 *
 * Valeurs (data-*-value) :
 *   - url           : endpoint d'upload (obligatoire)
 *   - maxFiles      : nombre max de fichiers (défaut 10)
 *   - maxFilesize   : taille max en Mo par fichier (défaut 5)
 *   - acceptedFiles : types MIME ou extensions acceptés, ex. "image/*,.pdf" (défaut "")
 *   - paramName     : nom du paramètre du fichier (défaut "file")
 *
 * Dark-mode : détecte la classe `.dark` sur <html> et ajoute la classe
 * `dz-dark` sur le conteneur Dropzone.
 *
 * Cycle de vie :
 *   connect()    → instancie Dropzone, stocke dans this._dz
 *   disconnect() → détruit l'instance (pas de fuite mémoire)
 */

// Désactiver la découverte automatique de Dropzone pour éviter les conflits
Dropzone.autoDiscover = false;

export default class extends Controller {
    static values = {
        url:           { type: String,  default: "/upload" },
        maxFiles:      { type: Number,  default: 10 },
        maxFilesize:   { type: Number,  default: 5 },
        acceptedFiles: { type: String,  default: "" },
        paramName:     { type: String,  default: "file" },
    };

    connect() {
        const isDark = document.documentElement.classList.contains("dark");

        const options = {
            url:            this.urlValue,
            maxFiles:       this.maxFilesValue,
            maxFilesize:    this.maxFilesizeValue,
            paramName:      this.paramNameValue,
            clickable:      true,
            // Messages localisés
            dictDefaultMessage:      "Glissez-déposez vos fichiers ici ou cliquez pour sélectionner",
            dictFallbackMessage:     "Votre navigateur ne supporte pas le glisser-déposer.",
            dictFallbackText:        "Utilisez le formulaire ci-dessous pour téléverser vos fichiers.",
            dictFileTooBig:          "Fichier trop volumineux ({{filesize}}Mo). Maximum : {{maxFilesize}}Mo.",
            dictInvalidFileType:     "Ce type de fichier n'est pas accepté.",
            dictResponseError:       "Le serveur a retourné une erreur {{statusCode}}.",
            dictCancelUpload:        "Annuler",
            dictUploadCanceled:      "Upload annulé.",
            dictCancelUploadConfirmation: "Confirmer l'annulation ?",
            dictRemoveFile:          "Supprimer",
            dictMaxFilesExceeded:    "Vous ne pouvez pas ajouter plus de fichiers.",
        };

        // Ajouter les types MIME si spécifiés
        if (this.acceptedFilesValue !== "") {
            options.acceptedFiles = this.acceptedFilesValue;
        }

        this._dz = new Dropzone(this.element, options);

        // Dark-mode : ajouter la classe sur le conteneur
        if (isDark) {
            this.element.classList.add("dz-dark");
        }

        // Mettre à jour le thème si le mode change (ex. thème switché pendant usage)
        this._dz.on("addedfile", () => {
            const dark = document.documentElement.classList.contains("dark");
            if (dark) {
                this.element.classList.add("dz-dark");
            } else {
                this.element.classList.remove("dz-dark");
            }
        });
    }

    disconnect() {
        if (this._dz) {
            this._dz.destroy();
            this._dz = null;
        }
    }
}
