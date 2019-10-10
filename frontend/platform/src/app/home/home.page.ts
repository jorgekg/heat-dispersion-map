import { Component } from '@angular/core';
import { AppStorageService } from '../core/app-storage/app-storage.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: 'home.page.html',
  styleUrls: ['home.page.scss'],
})
export class HomePage {

  constructor(
    private appStorageService: AppStorageService,
    private router: Router
  ) {}

  ionViewWillEnter() {
    const user = this.appStorageService.getUser();
    if (!user) {
      this.router.navigate(['login']);
    }
    if (user && !user.companyId) {
      this.router.navigate(['company/register/new'])
    }
  }

}
