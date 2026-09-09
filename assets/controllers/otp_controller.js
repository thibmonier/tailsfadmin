import { Controller } from "@hotwired/stimulus";

/**
 * Contrôleur Stimulus : tailsfadmin--otp
 * US-034 — Saisie de code OTP en champs segmentés.
 *
 * Câblage Twig :
 *   <div data-controller="tailsfadmin--otp">
 *     <input data-tailsfadmin--otp-target="digit"
 *            data-action="input->tailsfadmin--otp#onInput keydown->tailsfadmin--otp#onKeydown paste->tailsfadmin--otp#onPaste"
 *            inputmode="numeric" maxlength="1" />
 *     … (autant de champs que de chiffres)
 *   </div>
 *
 * Comportement : n'accepte qu'un chiffre par champ, avance le focus à la saisie,
 * recule sur Backspace quand le champ est vide, et répartit un code collé.
 */
export default class extends Controller {
    static targets = ["digit"];

    onInput(event) {
        const input = event.target;
        input.value = input.value.replace(/\D/g, "").slice(0, 1);
        if (input.value) {
            this.#focusAt(this.digitTargets.indexOf(input) + 1);
        }
    }

    onKeydown(event) {
        if ("Backspace" === event.key && "" === event.target.value) {
            this.#focusAt(this.digitTargets.indexOf(event.target) - 1);
        }
    }

    onPaste(event) {
        event.preventDefault();
        const text = (event.clipboardData?.getData("text") ?? "").replace(/\D/g, "");
        if ("" === text) {
            return;
        }
        this.digitTargets.forEach((digit, index) => {
            digit.value = text[index] ?? "";
        });
        this.#focusAt(Math.min(text.length, this.digitTargets.length - 1));
    }

    #focusAt(index) {
        const target = this.digitTargets[index];
        if (target) {
            target.focus();
            target.select();
        }
    }
}
