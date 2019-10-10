import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { UserService } from '../core/entities/user/user.service';
import { AppErrorsService } from '../core/app-errors/app-errors.service';
import { AppStorageService } from '../core/app-storage/app-storage.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-create-account',
  templateUrl: './create-account.page.html',
  styleUrls: ['./create-account.page.scss'],
})
export class CreateAccountPage implements OnInit {

  public formGroup: FormGroup;

  public btnLogin = 'Vamos lá';

  constructor(
    private formBuilder: FormBuilder,
    private userService: UserService,
    private appErrors: AppErrorsService,
    private appStorageService: AppStorageService,
    private router: Router
  ) { }

  ngOnInit() {
    this.formGroup = this.formBuilder.group({
      name: [null, Validators.compose([Validators.required])],
      email: [null, Validators.compose([Validators.required])],
      document: [null, Validators.compose([Validators.required])],
      password: [null, Validators.compose([Validators.required])]
    });
  }

  public async save() {
    if (this.formGroup.valid) {
      this.formGroup.disable();
      this.btnLogin = 'Aguarde...';
      try {
        const user = this.formGroup.getRawValue();
        await this.userService.create(user).toPromise();
        const token = await this.userService.login({
          email: user.email,
          password: user.password
        }).toPromise();
        this.appStorageService.setToken(token);
        this.router.navigate(['/home']);
      } catch (err) {
        if (err && err.status == 409) {
          this.appErrors.showMessageError('Este e-mail já encontra-se em nossa base de dados!', 'danger');
        } else {
          this.appErrors.showError(err);
        }
      }
    } else {
      this.formGroup.markAllAsTouched();
    }
    this.btnLogin = 'Vamos lá';
    this.formGroup.enable();
  }

}
