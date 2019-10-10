import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'app-validator',
  templateUrl: './validator.component.html',
  styleUrls: ['./validator.component.scss'],
})
export class ValidatorComponent {

  @Input() control;

  constructor() { }

  public getMessage() {
    if (this.control && this.control.errors && this.control.errors.required && this.control.touched) {
      return 'Este campo é obrigatório.'
    }
  }

}
